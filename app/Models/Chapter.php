<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';
    protected $fillable = ['title', 'slug', 'post_id', 'published_date'];

    const ITEM_PER_PAGE = 12;

    /**
     * @param $slug
     * @param $postId
     * @return array|mixed
     */
    public static function getChapterBySlug($slug, $postId)
    {
        $chapter = Chapter::where('post_id', $postId)->where('slug', $slug)->get();
        if (count($chapter)) { return $chapter[0]; }

        return  [];
    }
}
