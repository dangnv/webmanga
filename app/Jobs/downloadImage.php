<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use App\Constant\UtilsHelp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class downloadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imageId, $postId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($imageId = null, $postId = null)
    {
        $this->imageId = $imageId;
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $linkToDownLoad = '';
            $storage = '';
            if (!empty($this->postId)) {
                $post = Post::find($this->postId);
                if (empty($post)) {
                    Log::warning("Download image job: post not found, id = {$this->postId}");
                }
                $linkToDownLoad = $post->thumbnail;
                $storage = "manga/".$this->postId."/{$post->slug}_".time();
            } else if (!empty($this->imageId)) {
                $image = Image::find($this->imageId);
                if (empty($image) || empty($image->chapter) || empty($image->chapter->post)) {
                    Log::warning("Download image job: image not found, id = {$this->imageId}");
                }
                $linkToDownLoad = $image->url;
                $storage = "manga/".$image->chapter->post_id."/chapter_{$image->chapter_id}/{$image->chapter->post->slug}_{$image->chapter_id}_{$image->id}";
            }
            if (!empty($linkToDownLoad)) {
                $nameImage = UtilsHelp::getSlugFromLink($linkToDownLoad);
                $nameImage = explode('.', $nameImage);
                $imgPath = "{$storage}.{$nameImage[count($nameImage) - 1]}";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $linkToDownLoad);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_REFERER, 'https://mangakakalot.com/');
                $html = curl_exec($ch);

                $path = Storage::disk('mangamobi')->put($imgPath, $html, 'public');
                if ($path) {
                    curl_close($ch);
                    if (!empty($this->postId)) {
                        $post['thumbnail'] = $imgPath;
                        $post->save();
                    } else if (!empty($this->imageId)) {
                        $image['url'] = $imgPath;
                        $image->save();
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::info("Exception download iamge = {$exception->getMessage()}");
        }
    }
}
