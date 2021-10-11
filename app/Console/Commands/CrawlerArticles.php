<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CrawlerArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler chapters from site https://mangayeh.com/news';

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
        Log::info("Start crawler article".env("STOP_CRAWLER_POST=true"));
        $link = 'https://mangayeh.com/news';
        try {
            $page = 1;
            do {
                $linkByPage = "{$link}?page={$page}";
                $html = file_get_html($linkByPage);
                if ($html->find('div.column.is-9') && $html->find('div.column.is-9')[0]->find('div.columns.is-multiline')) {
                    $boxs = $html->find('div.column.is-9')[0]->find('div.columns.is-multiline')[0]->find('div.column.is-12');
                    foreach ($boxs as $item) {
                        $name = '';
                        $publicAt = '';
                        $desc = '';
                        if ($item->find('img.bthumbnail')) {
                            $thumbnail = self::downloadImageFromLink($item->find('img.bthumbnail')[0]->getAttribute('data-src'));
                        } else {
                            break;
                        }
                        if ($item->find('div.mtitle')) {
                            $name = $item->find('div.mtitle')[0]->innertext;
                        }
                        if ($item->find('p.msub')) {
                            $publicAt = $item->find('p.msub')[0]->innertext;
                        }
                        if ($item->find('div.ellipsis.is-ellipsis-2.is-fixed-bottom.is-hidden-mobile')) {
                            $desc = $item->find('div.ellipsis.is-ellipsis-2.is-fixed-bottom.is-hidden-mobile')[0]->innertext;
                        }
                        if ($item->find('a.box.is-shadowless')) {
                            $linkDetailNew = "https://mangayeh.com".$item->find('a.box.is-shadowless')[0]->getAttribute('href');
                            $slug = explode('/', $linkDetailNew);
                            $slug = $slug[count($slug) - 1];
                            if (Article::select(DB::raw('count(id)'))->where('slug', $slug)->count() == 0) {
                                try {
                                    $newHtml = file_get_html($linkDetailNew);
                                    if($newHtml->find('div.content')) {
                                        $content = $newHtml->find('div.content')[0]->innertext;
                                        Article::create([
                                            'thumbnail' => $thumbnail,
                                            'title' => $name,
                                            'public_at' => Carbon::create($publicAt)->format('Y-m-d'),
                                            'slug' => $slug,
                                            'description' => $desc,
                                            'content' => str_replace('data-src', 'src', str_replace('src="/image/default.gif"', '', $content))
                                        ]);
                                    }
                                } catch (\Exception $exception) {
                                    Log::info("Exception get detail new, ".$exception->getMessage());
                                }
                            } else {
                                Log::info('No new article');
                                return true;
                            }
                        }
                    }
                }
                $page++;
            } while (1);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception}");
        }
    }

    public static function downloadImageFromLink ($link)
    {
        try {
            $linkArr = explode('/', $link);
            $nameImage = $linkArr[count($linkArr) - 1];
            $imgPath = "news/{$nameImage}.png";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_REFERER, 'https://mangayeh.com');
            $html = curl_exec($ch);
            $path = Storage::disk('mangamobi')->put($imgPath, $html, 'public');
            if (!$path) { return $link; }
            curl_close($ch);
            return $imgPath;
        } catch (\Exception $exception) {
            Log::error("Exception download image news = {$exception->getMessage()}");
            return $link;
        }
    }
}
