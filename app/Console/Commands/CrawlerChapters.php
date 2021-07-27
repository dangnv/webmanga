<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrawlerChapters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:chapters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler chapters from site https://mangayeh.com/';

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
        $posts = Post::select('slug', 'id', 'thumbnail')->orderBy('id', 'asc')->get();
        $linkDomain = 'https://mangayeh.com';
        foreach ($posts as $post) {
            $linkForDetails = "{$linkDomain}/manga/{$post->slug}";
            try {
                $html = file_get_html($linkForDetails);
                $thumbnail = $html->find('img.athumbnail')[0]->getAttribute('data-src');
                $post['thumbnail'] = $thumbnail;
                $post->update();
                echo "\n\nUpdated {$post->title}";
                $boxs = $html->find('div#chapterList');
                if ($boxs) {
                    $boxs = $boxs[0]->find('tbody');
                    if ($boxs) {
                        $trs = $boxs[0]->find('tr');
                        foreach ($trs as $key => $tr) {
                            if (count($tr->find('td')) > 2 && $tr->find('td')[0]->find('a')) {
                                $chapter = $tr->find('td')[0]->find('a')[0];
                                $link = $chapter->getAttribute('href');
                                $slug = explode('/', $link);
                                $slug = $slug[count($slug) - 1];
                                if (Chapter::select(DB::raw('count(id)'))->where('slug', $slug)->count() == 0) {
                                    $name = $chapter->innertext;
                                    $publishedDate = $tr->find('td')[1]->innertext;
                                    echo "\n\n\nLink post = ", $linkForDetails;
                                    echo "\nName chapter = ", $name;
                                    $chapterSaved = Chapter::create([
                                        'title' => $name,
                                        'slug' => $slug,
                                        'post_id' => $post->id,
                                        'published_date' => Carbon::create($publishedDate)->format('Y-m-d H:m:s')
                                    ]);
                                    for ($i = 0; $i < 50; $i++) {
                                        Image::create([
                                            'url' => 'https://static1.mangayeh.com/manga/chapter/60191e24a566934fb06d14e7/60fcee37df3f463b9139ad3b/tales_of_the_unusual_320_52.jpg',
                                            'chapter_id' => $chapterSaved->id
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "\n Exception = ".$exception->getMessage();
            }
        }
    }
}
