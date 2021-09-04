<?php

namespace App\Jobs;

use App\Models\PostCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class createCategoryPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $postId, $lstCategories;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($postId, $lstCategories)
    {
        $this->postId = $postId;
        $this->lstCategories = $lstCategories;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($this->lstCategories as $cateId) {
                PostCategory::create([
                    'category_id' => $cateId,
                    'post_id' => $this->postId,
                ]);
            }
        } catch (\Exception $exception) {
            Log::warning("Exception create post category: {$exception->getMessage()}");
        }
    }
}
