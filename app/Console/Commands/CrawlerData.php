<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrawlerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:data {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler data from site https://mangayeh.com/';

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
        Log::info("Run it");
        $linkDomain = 'https://mangayeh.com';
        $typeGet = $this->option('type');
        if ($typeGet == 'post') {
            $link = 'https://mangayeh.com/category/all';
            $page = 1;
            $end = false;
            if (!empty($link)) {
                do {
                    $linkByPage = "{$link}?page={$page}";
                    $html = file_get_html($linkByPage);
                    $boxPosts = $html->find('section.section');
                    if ($boxPosts) {
                        $boxPosts = $boxPosts[0]->find('div.columns.mt-2');
                        if ($boxPosts) {
                            $boxPosts = $boxPosts[0]->find('div.columns.is-multiline');
                            if ($boxPosts) {
                                $boxPosts = $boxPosts[0]->find('div.column.is-half');
                                if (count($boxPosts) > 0) {
                                    foreach ($boxPosts as $post) {
                                        $linkPost = $post->find('a.box.is-shadowless');
                                        if ($linkPost) {
                                            $status = $post->find('p.msub');
                                            if ($status && str_contains('Ongoing', $status[0])) {
                                                $status = Post::STATUS_ON_GOING;
                                            } else {
                                                $status = Post::STATUS_COMPLETED;
                                            }
                                            $isNew = $post->find('div.mtitle');
                                            if ($isNew && $isNew[0]->find('span.tag.is-success')) {
                                                $isNew = Post::STATUS_NEW;
                                            } else {
                                                $isNew = Post::STATUS_NOT_NEW;
                                            }
                                            $linkPost = $linkPost[0]->getAttribute('href');
                                            if ($linkPost) {
                                                echo "\nPost = ".$post->find('div.mtitle')[0]->innertext;
                                                self::getPost($linkDomain.$linkPost, $status, $isNew);
                                            }
                                        }
                                    }
                                } else {
                                    $end = true;
                                    break;
                                }
                            }
                        }
                    }
                    echo "\nPages = ".$page;
                    $page++;
                } while (!$end);
            }
        } else if ($typeGet == 'category') {
            $link = 'https://mangayeh.com';
            $html = file_get_html($link);
            $boxCategories = $html->find('div.column.is-3.pt-0');
            if (count($boxCategories) > 0) {
                $boxCategories = $boxCategories[0]->find('div.column.is-half.is-paddingless');
                if ($boxCategories) {
                    foreach ($boxCategories as $cate) {
                        $element = $cate->find('a');
                        if ($element) {
                            $cate = [];
                            $name = $element[0]->innertext;
                            $slug = $element[0]->getAttribute('href');
                            $slug = explode('/', $slug);
                            $cate['slug'] = $slug[count($slug) - 1];
                            $cate['name'] = $name;

                            Category::create($cate);
                            echo "\nCreated category: ".$name;
                        }
                    }
                }
            }
        } else if ($typeGet == 'tag') {
            $link = 'https://mangayeh.com';
            $html = file_get_html($link);
            $boxTags = $html->find('div.column.is-3.pt-0');
            if (count($boxTags) > 0) {
                $boxTags = $boxTags[0]->find('a.tag.is-success');
                if ($boxTags) {
                    foreach ($boxTags as $tag) {
                        $name = $tag->innertext;
                        echo "\nInserted {$name}";
                        if (Tag::select(DB::raw('count(id)'))->where('name', $name)->count() == 0) {
                            $tag = new Tag(['name' => $name]);
                            $tag->save();
                            $linkTagSearch = str_replace(' ', '%20', $tag->getAttribute('href'));
                        } else {
                            $tag = Tag::where('name', $name)->first();
                            $linkTagSearch = str_replace(' ', '%20', "search?q={$tag->name}");
                        }

                        try {
                            $page = 1;
                            do {
                                echo "\n\n\nPage {$page}";
                                $linkTagSearchByPage = "{$link}/{$linkTagSearch}?page={$page}";
                                $searchHtml = file_get_html($linkTagSearchByPage);
                                if ($searchHtml->find('div.columns.mt-2') && $searchHtml->find('div.columns.mt-2')[0]->find('div.column.is-9') && $searchHtml->find('div.columns.mt-2')[0]->find('div.column.is-9')[0]->find('a.box.is-shadowless')) {
                                    $posts = $searchHtml->find('div.columns.mt-2')[0]->find('div.column.is-9')[0]->find('div.columns.is-multiline')[0]->find('a.box.is-shadowless');
                                    foreach ($posts as $post) {
                                        $slug = explode('/', $post->getAttribute('href'));
                                        $slug = $slug[count($slug) - 1];
                                        $postId = Post::select('id')->where('slug', $slug)->first();
                                        if ($postId) {
                                            echo "\nTag for post, id = {$postId}";
                                            $postId = $postId->id;
                                            PostTag::create([
                                                'post_id' => $postId,
                                                'tag_id' => $tag->id
                                            ]);
                                        }
                                    }
                                } else {
                                    break;
                                }
                                $page++;
                            } while (1);
                        } catch (\Exception $exception) {
                            echo "\n Exception: {$exception->getMessage()}";
                        }
                    }
                }
            }
        }
    }

    public static function getPost ($link, $status, $isNew)
    {
        $post = [];
        $cateIds = [];
        $slug = explode('/', $link);
        $post['slug'] = $slug[count($slug) - 1];
        $post['status'] = $status;
        $post['status'] = $isNew;

        try {
            $html = file_get_html($link);
            $infoPart = $html->find('div.columns');
            if ($infoPart) {
                $info = $infoPart[0]->find('article.media');
                if ($info) {
                    $avatar = $info[0]->find('img.athumbnail');
                    if ($avatar) {
                        $post['thumbnail'] = $avatar[0]->getAttribute('src');
                    }

                    $title = $info[0]->find('h1.title');
                    if ($title) {
                        $post['title'] = $title[0]->innertext;
                    }

                    $views = 0;
                    $viewPart = $info[0]->find('p.is-hidden-tablet');
                    if ($viewPart) {
                        $viewPart = $viewPart[0]->innertext;
                        if ($viewPart) {
                            $viewPart = explode(' - ', $viewPart);
                            if ($viewPart) {
                                $viewPart = explode(' reviews', $viewPart[1]);
                                if ($viewPart[0]) {
                                    $views = (int)$viewPart[0];
                                }
                            }
                        }
                    }
                    $post['view'] = $views;
                }

                $overView = $infoPart[0]->find('tbody');
                if ($overView) {
                    $columnsOnTable = $overView[0]->find('tr');
                    if (count($columnsOnTable) > 0) {
                        foreach ($columnsOnTable as $tr) {
                            $tds = $tr->find('td');
                            if (count($tds) > 0) {
                                if ($tds[0]->innertext == 'Alt names:' || $tds[0]->innertext == 'Demographic:' || $tds[0]->innertext == 'Format:' || $tds[0]->innertext == 'Genre:') {
                                    if ($tds[0]->innertext == 'Alt names:') $post['alt_names'] = self::getTextFromTags($tds[1]);
                                    if ($tds[0]->innertext == 'Demographic:') $post['alt_names'] = self::getTextFromTags($tds[1]);
                                    if ($tds[0]->innertext == 'Format:') $post['format'] = self::getTextFromTags($tds[1]);
                                    if ($tds[0]->innertext == 'Genre:') $cateIds = self::getTextFromTags($tds[1], true);
                                }
                                if ($tds[0]->innertext == 'Author:' || $tds[0]->innertext == 'Artist:') {
                                    $a = $tds[1]->find('a');
                                    if ($a) {
                                        if ($tds[0]->innertext == 'Author:') $post['author'] = $a[0]->innertext;
                                        if ($tds[0]->innertext == 'Artist:') $post['artist'] = $a[0]->innertext;
                                    }
                                }
                            }
                        }
                    }
                }

                $content = $infoPart[0]->find('div.content');
                if ($content) {
                    $post['description'] = $content[0]->innertext;
                }

                echo "\npost name = ".$post['title'];
                if (Post::select(DB::raw('count(id)'))->where('slug', $post['slug'])->count() == 0) {
                    echo "\npost created\n";
                    $post = Post::create($post);
                    foreach ($cateIds as $cateId) {
                        PostCategory::create([
                            'post_id' => $post->id,
                            'category_id' => $cateId
                        ]);
                    }
                }
            }
        } catch (\Exception $exception) {
            echo "exception = ".$exception->getMessage();
        }
    }

    public static function getTextFromTags ($el, $isCate = false)
    {
        $str = [];
        if ($el) {
            $spans = $el->find('span');
            if ($spans) {
                foreach ($spans as $span) {
                    $value = $span->innertext;
                    if ($isCate) {
                        $value = Category::select('id')->where('name', $value)->first();
                        if ($value) {
                            $value = $value->id;
                        } else {
                            continue;
                        }
                    }
                    $str[] = $value;
                }
            }
        }

        return $isCate ? $str : implode(',', $str);
    }
}
