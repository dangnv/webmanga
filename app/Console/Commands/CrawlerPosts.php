<?php

namespace App\Console\Commands;

use App\Jobs\createCategoryPost;
use App\Jobs\createLstChapters;
use App\Jobs\downloadImage;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostCategory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Constant\UtilsHelp;

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
        Log::info('start job crawler posts');
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
                            Log::debug("Allow crawler posts = {$pageLast}");
                            for ($page = 1; $page <= $pageLast; $page++) {
                                if (!$checkNewPost) { break; }
                                if ($page != 1) {
                                    $html = file_get_html("{$link}/{$page}");
                                }

                                /** Get list categories */
                                if ($page == 1 && $html->find('div.panel-genres-list')) {
                                    $categoriesHtml = $html->find('div.panel-genres-list')[0]->find('a');
                                    if (count($categoriesHtml) > (Category::count() + 1)) {
                                        foreach ($categoriesHtml as $key => $category) {
                                            if ($key != 0) {
                                                $slugCate = UtilsHelp::getSlugFromLink($category->getAttribute('href'));
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
                                        if (Post::count() >= 650) {
                                            Log::debug('Created 650 posts');
                                            $checkNewPost = false;
                                            break;
                                        }
                                        $freeStorage = UtilsHelp::checkStorage();
                                        if ($freeStorage <= 10) {
                                            Log::warning("Storage limit, free {$freeStorage}, stop at ".Post::count());
                                            $checkNewPost = false;
                                            break;
                                        }

                                        Log::debug("number of posts = ".Post::count());
                                        if (!$post->find('a.genres-item-img')) { continue; }
                                        $a = $post->find('a.genres-item-img')[0];
                                        if (!$a->find('img')) { continue; }

                                        $linkToPostDetail = $a->getAttribute('href');
                                        $slugFromLinkPost = UtilsHelp::getSlugFromLink($linkToPostDetail);
                                        if (Post::select('id')->where('title', $a->getAttribute('title'))->count() > 0) {
                                            if ($post->find('span.genres-item-time')) {
                                                $timeLastUpdate = $post->find('span.genres-item-time')[0]->innertext;
                                                if (Carbon::create($timeLastUpdate) > Carbon::now()->format('Y-m-d') ||
                                                in_array($slugFromLinkPost, ['manga-fn983148', 'manga-cm979547', 'manga-fd982360'])) {
                                                    $postDB = Post::where('title', $a->getAttribute('title'))->first();
                                                    self::updatePostOld($linkToPostDetail, $postDB->id);
                                                }
                                            }
                                            continue;
                                            /*if (env('IS_INIT')) { continue; }
                                            $checkNewPost = false;
                                            break;*/
                                        }
                                        $post = self::getDetailInfo($linkToPostDetail);
                                        $post['thumbnail'] = $a->find('img')[0]->getAttribute('src');
                                        $post['title'] = $a->getAttribute('title');
                                        $post['is_new'] = count($a->find('em.genres-item-new')) > 0 ? Post::STATUS_NEW : Post::STATUS_NOT_NEW;
                                        $post['views'] = 0;

                                        if (empty($post['title'])) {
                                            Log::error("Title is empty {$linkToPostDetail}");
                                            continue;
                                        }

                                        Log::debug("Created post");
                                        $postCreated = new Post($post);
                                        $postCreated->save();
                                        downloadImage::dispatch(null, $postCreated->id)->onQueue('download');

                                        /** Create post category */
                                        createCategoryPost::dispatch($postCreated->id, $post['categories'])->onQueue('cate_post');

                                        /** Create chapters list */
                                        createLstChapters::dispatch($postCreated->id, $post['chapters'])->onQueue('chapter');
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
                    $chapter['slug'] = UtilsHelp::getSlugFromLink($chapter['link']);
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
            $slug = UtilsHelp::getSlugFromLink($categoryName->getAttribute('href'));

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
                    $chapter['slug'] = UtilsHelp::getSlugFromLink($chapter['link']);
                    if ($chapterBox->find('span.chapter-time')) {
                        $chapter['published_date'] = Carbon::create($chapterBox->find('span.chapter-time')[0]->getAttribute('title'));
                        if ($chapter['published_date']) { $chapter['published_date'] = $chapter['published_date']->format('Y-m-d H:i:m'); }
                    }
                    $lstNewChapters[] = $chapter;
                }
            }
            createLstChapters::dispatch($postID, $lstNewChapters)->onQueue('chapter');
        }
    }
}
