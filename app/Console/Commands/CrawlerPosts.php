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
use Faker\Provider\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CrawlerPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:manganato';

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
                Log::debug("Start crawler");
                $html = file_get_html("{$link}");
                /** Get number of pages */
                if ($html->find('.panel-page-number')) {
                    $elLastPage = $html->find('.panel-page-number')[0]->find('a.page-last');
                    if ($elLastPage) {
                        $pageLast = (int)str_replace(')', '', str_replace('LAST(', '', $elLastPage[0]->innertext));
                        if ($pageLast > 1) {
                            $checkNewPost = true;
                            for ($page = 1; $page <= $pageLast; $page++) {
                                if (!$checkNewPost) { break; }
                                if (Post::count() >= 200) { break; }
                                if ($page != 1) {
                                    $html = file_get_html("{$link}/{$page}");
                                }

                                /** Get list categories */
                                if ($page == 1 && $html->find('div.panel-genres-list')) {
                                    $categoriesHtml = $html->find('div.panel-genres-list')[0]->find('a');
                                    if (count($categoriesHtml) > (Category::count() + 1)) {
                                        foreach ($categoriesHtml as $key => $category) {
                                            if ($key != 0) {
                                                $slugCate = self::getSlugFromLink($category->getAttribute('href'));
                                                $nameCate = str_replace(' Manga', '', $category->getAttribute('title'));
                                                $rsCreateCate = self::saveNewCategory($slugCate, $nameCate, true);
                                            }
                                        }
                                    }
                                }

                                /** Get list posts */
                                if ($html->find('.content-genres-item')) {
                                    $boxPosts = $html->find('div.content-genres-item');

                                    foreach ($boxPosts as $post) {
                                        Log::debug("number of posts = ".Post::count());
                                        if (Post::count() >= 200) { Log::debug("DONE"); break; }
                                        if (!$post->find('a.genres-item-img')) { continue; }
                                        $a = $post->find('a.genres-item-img')[0];
                                        if (!$a->find('img')) { continue; }

                                        $linkToPostDetail = $a->getAttribute('href');
                                        if (Post::select('id')->where('slug', self::getSlugFromLink($linkToPostDetail))->count() > 0) {
                                            if ($post->find('span.genres-item-time')) {
                                                $timeLastUpdate = $post->find('span.genres-item-time')[0]->innertext;
                                                if (Carbon::create($timeLastUpdate) > Carbon::now()->format('Y-m-d')) {
                                                    $postDB = Post::getPostBySlug(self::getSlugFromLink($linkToPostDetail));
                                                    self::updatePostOld($linkToPostDetail, $postDB->id);
                                                }
                                            }
                                            $checkNewPost = false;
                                            break;
                                        }
                                        $post = self::getDetailInfo($linkToPostDetail);
                                        $post['thumbnail'] = self::downloadImageFromLink($a->find('img')[0]->getAttribute('src'), 'images/'.self::getSlugFromLink($linkToPostDetail)); /** Download thumbnail */
                                        $post['title'] = $a->getAttribute('title');
                                        $post['slug'] = self::getSlugFromLink($linkToPostDetail);
                                        $post['is_new'] = count($a->find('em.genres-item-new')) > 0 ? Post::STATUS_NEW : Post::STATUS_NOT_NEW;
                                        $post['views'] = 0;

                                        Log::debug("Created post");
                                        $postCreated = Post::create($post);

                                        /** Create post category */
                                        self::createCategoryPost($postCreated->id, $post['categories']);

                                        /** Create chapters list */
                                        self::createLstChapters($postCreated->id, $post['chapters']);
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                Log::info("Exception create post = {$exception->getMessage()}");
            }
        }
        Log::info("End job crawler data");
    }

    public static function getSlugFromLink ($link)
    {
        $linkArr = explode('/', $link);
        $slugArr = explode('?', $linkArr[count($linkArr) - 1]);
        return $slugArr[0];
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
            Storage::makeDirectory("public/$storage");
            $imgPath = public_path("storage/{$storage}/{$nameImage}");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, 'https://mangakakalot.com/');
            $html = curl_exec($ch);
            curl_close($ch);
            $savefile = fopen($imgPath, 'w');
            fwrite($savefile, $html);
            fclose($savefile);
            return "/storage/{$storage}/{$nameImage}";
        } catch (\Exception $exception) {
            Log::info("Exception download iamge = {$exception->getMessage()}");
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
                        $post['categories'] = [];
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
        $post['chapters'] = [];
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

            $cateId = self::saveNewCategory($slug, $name);
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
                return $category[0]->id;
            }
            return 0;
        } catch (\Exception $exception) {
            Log::info("Exceptions when create new category: {$exception->getMessage()}");
            return 0;
        }
    }

    public static function createCategoryPost ($postId, $cateIds) {
        try {
            foreach ($cateIds as $cateId) {
                PostCategory::create([
                    'category_id' => $cateId,
                    'post_id' => $postId,
                ]);
            }
        } catch (\Exception $exception) {
            Log::info("Exception create post category: {$exception->getMessage()}");
        }
    }

    /**
     * @param $postId
     * @param $chapters
     */
    public static function createLstChapters ($postId, $chapters) {
        foreach ($chapters as $data) {
            try {
                $data['post_id'] = $postId;
                $chapter = Chapter::create($data);
                if ($chapter) {
                    $html = file_get_html($data['link']);
                    if ($html->find('.container-chapter-reader')) {
                        $images = $html->find('.container-chapter-reader')[0]->find('img');
                        foreach ($images as $image) {
                            $url = $image->getAttribute('src');
                            $postDB = Post::find($postId);
                            Image::create([
                                'chapter_id' => $chapter->id,
                                'url' => self::downloadImageFromLink($url, 'images/'.$postDB->slug)
                            ]);
                        }
                    }
                }
            } catch (\Exception $exception) {
                Log::info("Exception for create chapters: {$exception->getMessage()}");
                continue;
            }
        }
    }

    public static function updatePostOld ($linkDetail, $postID) {
        $html = file_get_html($linkDetail);

        /** Get list chapters */
        if ($html->find('ul.row-content-chapter')) {
            $lstNewChapters = [];
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
                    $chapterCheck = Chapter::getChapterBySlug($chapter['slug'], $postID);
                    if (empty($chapterCheck)) {
                        break;
                    }
                    $lstNewChapters[] = $chapter;
                }
            }

            self::createLstChapters($postID, $lstNewChapters);
        }
    }
}
