<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrawlerPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler data from site https://manganato.com/genre-all';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('start job');
        $link = 'https://manganato.com/genre-all';
        if (!empty($link)) {
            try {
                $html = file_get_html("{$link}?type=newest");
                /** Get number of pages */
                if ($html->find('.panel-page-number')) {
                    $elLastPage = $html->find('.panel-page-number')[0]->find('a.page-last');
                    if ($elLastPage) {
                        $pageLast = (int)str_replace(')', '', str_replace('LAST(', '', $elLastPage[0]->innertext));
                        if ($pageLast > 1) {
                            echo "\nTotal page = {$pageLast}\n\n\n";
                            for ($page = 1; $page <= $pageLast; $page++) {
                                if ($page != 1) {
                                    $html = file_get_html("{$link}/{$page}?type=newest");
                                }
                                echo "\nPages = ".$page;

                                /** Get list categories */
                                if ($page == 1 && $html->find('div.panel-genres-list')) {
                                    echo "\n Get list categories";
                                    $categoriesHtml = $html->find('div.panel-genres-list')[0]->find('a');
                                    if (count($categoriesHtml) > (Category::count() + 1)) {
                                        foreach ($categoriesHtml as $key => $category) {
                                            if ($key != 0) {
                                                $slugCate = self::getSlugFromLink($category->getAttribute('href'));
                                                $nameCate = str_replace(' Manga', '', $category->getAttribute('title'));
                                                $rsCreateCate = self::saveNewCategory($slugCate, $nameCate, true);
                                                if ($rsCreateCate != -1) { echo "\nCreate category: {$nameCate}"; }
                                            }
                                        }
                                    }
                                }

                                /** Get list posts */
                                if ($html->find('.content-genres-item')) {
                                    $boxPosts = $html->find('div.content-genres-item');
                                    echo "\n Get list posts";

                                    foreach ($boxPosts as $post) {
                                        if (!$post->find('a.genres-item-img')) { continue; }
                                        $a = $post->find('a.genres-item-img')[0];
                                        if (!$a->find('img')) { continue; }

                                        $linkToPostDetail = $a->getAttribute('href');
                                        if (Post::select('id')->where('slug', self::getSlugFromLink($linkToPostDetail))->count() > 0) {
                                            echo "\n\nPost old. Stop";
                                            break;
                                        }
                                        $post = self::getDetailInfo($linkToPostDetail);
                                        $post['thumbnail'] = self::downloadImageFromLink($a->find('img')[0]->getAttribute('src')); /** Download thumbnail */
                                        $post['title'] = $a->getAttribute('title');
                                        $post['slug'] = self::getSlugFromLink($linkToPostDetail);
                                        $post['is_new'] = count($a->find('em.genres-item-new')) > 0 ? Post::STATUS_NEW : Post::STATUS_NOT_NEW;
                                        $post['views'] = 0;
                                        $postCreated = Post::create($post);

                                        /** Create post category */
                                        self::createCategoryPost($postCreated->id, $post['categories']);

                                        /** Create chapters list */
                                        self::createLstChapters($postCreated->id, $post['chapters']);

                                        echo "\nCreated post {$post['title']}";
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "\n\nException create post = {$exception->getMessage()}";
            }
        }
    }

    public static function getSlugFromLink ($link)
    {
        $linkArr = explode('/', $link);
        return $linkArr[count($linkArr) - 1];
    }

    /**
     * @param $link
     * @param string $storage
     * @param string $beforeName
     * @return string
     */
    public static function downloadImageFromLink ($link, string $storage = 'images/posts', string $beforeName = ''): string
    {
        try {
            if (empty($beforeName)) { $beforeName = time(); }
            $nameImage = $beforeName.self::getSlugFromLink($link);
            $imgPath = public_path("{$storage}/{$nameImage}");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, 'https://mangakakalot.com/');
            $html = curl_exec($ch);
            curl_close($ch);
            $savefile = fopen($imgPath, 'w');
            fwrite($savefile, $html);
            fclose($savefile);
            echo "\nLưu ảnh thành công!\n";
            return "/{$storage}/{$nameImage}";
        } catch (\Exception $exception) {
            echo "\nException download iamge = {$exception->getMessage()}";
            return $link;
        }
    }

    /**
     * @param $linkDetail
     * @return array
     */
    public static function getDetailInfo ($linkDetail)
    {
        $post = [];
        $html = file_get_html($linkDetail);
        if ($html->find('div.panel-story-info')
         && $html->find('div.panel-story-info')[0]->find('.story-info-right')
         && $html->find('div.panel-story-info')[0]->find('.panel-story-info-description')) {
            $info = $html->find('div.panel-story-info')[0]->find('.story-info-right')[0];
            if ($info->find('table')) {
                $tableInfo = $info->find('table')[0]->find('tbody')[0]->find('tr');
                foreach ($tableInfo as $tr) {
                    if ($tr->find('td.table-label') && $tr->find('td.table-value')) {
                        $key = $tr->find('td.table-label')[0]->innertext;
                        /** @get Alternative */
                        if (strpos($key, 'Alternative') !== false) {
                            $value = $tr->find('td.table-value')[0];
                            if ($value && $value->find('h2')) {
                                $post['alt_names'] = $value->find('h2')[0]->innertext;
                            }
                        }
                        /** @get Author */
                        if (strpos($key, 'Author') !== false) {
                            $value = $tr->find('td.table-value')[0];
                            if ($value && $value->find('a')) {
                                $post['author'] = $value->find('a')[0]->innertext;
                            }
                        }
                        /** @get Status */
                        if (strpos($key, 'Status') !== false) {
                            $value = $tr->find('td.table-value')[0];
                            $post['status'] = $value->innertext == 'Ongoing' ? Post::STATUS_ON_GOING : Post::STATUS_COMPLETED;
                        }
                        /** @get category */
                        if (strpos($key, 'Genres') !== false) {
                            $value = $tr->find('td.table-value')[0];
                            if ($value && $value->find('a')) {
                                $post['categories'] = self::getCategories($value->find('a'));
                            }
                        }
                    }
                }
            }

            $description = $html->find('div.panel-story-info')[0]->find('.panel-story-info-description')[0];
            $titleDesc = $description->find('h3')[0]->outertext;
            $post['description'] = str_replace($titleDesc, '', $description->innertext);
        }

        /** Get list chapters */
        if ($html->find('ul.row-content-chapter')) {
            $listChaptersLink = $html->find('ul.row-content-chapter')[0]->find('li');
            foreach ($listChaptersLink as $chapterBox) {
                if ($chapterBox->find('a')) {
                    $chapterA = $chapterBox->find('a')[0];
                    $chapter['title'] = $chapterA->innertext;
                    $chapter['link'] = $chapterA->getAttribute('href');
                    $chapter['slug'] = self::getSlugFromLink($chapter['link']);
                    if ($chapterBox->find('span.chapter-time')) {
                        $chapter['published_date'] = Carbon::create($chapterBox->find('span.chapter-time')[0]->getAttribute('title'));
                        if ($chapter['published_date']) { $chapter['published_date'] = $chapter['published_date']->format('Y-m-d H:i:m'); }
                    }
                    $post['chapters'][] = $chapter;
                }
            }
        }

        return $post;
    }

    /**
     * @param $arrElements
     * @return array
     */
    public static function getCategories($arrElements)
    {
        $categories = [];
        foreach ($arrElements as $categoryName) {
            $name = $categoryName->innertext;
            $slug = self::getSlugFromLink($categoryName->getAttribute('href'));
            echo "\n\nSlug cate = {$slug}";

            $cateId = self::saveNewCategory($slug, $name);
            echo "\nResult find cateId = {$cateId}";
            if ($cateId > 0) {
                $categories[] = $cateId;
            }
        }

        return $categories;
    }

    /**
     * @param $slug
     * @param $name
     * @param bool $onlyCheckExist
     * @return int
     */
    public static function saveNewCategory($slug, $name, bool $onlyCheckExist = false)
    {
        try {
            echo "\nsave new categpry, name = {$name}, slug = {$slug}";
            if (Category::select('id')->where('slug', $slug)->count() == 0) {
                Category::create([
                    'slug' => $slug,
                    'name' => $name
                ]);
            } else if ($onlyCheckExist) {
                return -1;
            }

            $category = Category::select('id')->where('slug', $slug)->get();

            if (count($category) > 0) {
                echo "\nVo day = ".$category[0]->id;
                return $category[0]->id;
            }
            echo "\n ra ngoai";
            return 0;
        } catch (\Exception $exception) {
            echo "\nExceptions when create new category: {$exception->getMessage()}";
            return 0;
        }
    }

    public static function createCategoryPost ($postId, $cateIds) {
        try {
            foreach ($cateIds as $cateId) {
                echo "\nCreate category post: {$postId}, {$cateId}";
                PostCategory::create([
                    'category_id' => $cateId,
                    'post_id' => $postId,
                ]);
            }
        } catch (\Exception $exception) {
            echo "\n\nException create post category: {$exception->getMessage()}";
        }
    }

    /**
     * @param $postId
     * @param $chapters
     */
    public static function createLstChapters ($postId, $chapters) {
        foreach ($chapters as $data) {
            try {
                echo "\nCreate lst chapters {$data['title']}, {$data['link']}";
                $data['post_id'] = $postId;
                $chapter = Chapter::create($data);
                if ($chapter) {
                    $html = file_get_html($data['link']);
                    if ($html->find('.container-chapter-reader')) {
                        $images = $html->find('.container-chapter-reader')[0]->find('img');
                        foreach ($images as $image) {
                            $url = $image->getAttribute('src');
                            echo "\nUrl = {$url}";
                            Image::create([
                                'chapter_id' => $chapter->id,
                                'url' => self::downloadImageFromLink($url, "images/chapters")
                            ]);
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "\n\nException for create chapters: {$exception->getMessage()}";
                continue;
            }
        }
    }
}
