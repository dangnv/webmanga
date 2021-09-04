<?php

namespace App\Jobs;

use App\Models\Chapter;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class createLstChapters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $postId, $lstChapters;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($postId, $lstChapters)
    {
        $this->postId = $postId;
        $this->lstChapters = $lstChapters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->lstChapters as $data) {
            Log::debug('create chapters');
            try {
                $chapterCheck = Chapter::getChapterBySlug($data['slug'], $this->postId);
                if (!empty($chapterCheck)) {
                    continue;
                }
                $data['post_id'] = $this->postId;
                $chapter = Chapter::create($data);
                if ($chapter) {
                    $html = file_get_html($data['link']);
                    if ($html->find('.container-chapter-reader')) {
                        $images = $html->find('.container-chapter-reader')[0]->find('img');
                        foreach ($images as $image) {
                            $url = $image->getAttribute('src');
                            $imageCreated = Image::create([
                                'chapter_id' => $chapter->id,
                                'url' => $url
                            ]);
                            downloadImage::dispatch($imageCreated->id)->onQueue('download');
                        }
                    }
                }
            } catch (\Exception $exception) {
                Log::warning("Exception for create chapters: {$exception->getMessage()}");
                continue;
            }
        }
    }
}
