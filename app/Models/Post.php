<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $table = 'posts';
    protected $fillable = ['id', 'thumbnail', 'title', 'slug', 'status', 'is_new', 'alt_names', 'author', 'artist', 'demographic', 'format', 'description', 'views'];

    const STATUS_ON_GOING   = 0;
    const STATUS_COMPLETED  = 1;

    const STATUS_NEW        = 1;
    const STATUS_NOT_NEW    = 0;

    const ITEM_PER_PAGE = 40;
    const CURRENT_PAGE  = 1;

    public function tags()
    {
        return $this->hasMany(PostTag::class, 'post_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(PostCategory::class, 'post_id', 'id');
    }

    public function chapters ()
    {
        return $this->hasMany(Chapter::class, 'post_id', 'id');
    }

    public function comments ()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id')->orderBy('created_at', 'desc');
    }

    public static function getLastChapter($id)
    {
        return Chapter::select('*')
                        ->where('post_id', $id)
                        ->orderBy('published_date', 'desc')
                        ->take(1)
                        ->get();
    }

    /**
     * @return array|mixed
     */
    public static function getPostBySlug($slug)
    {
        $post = self::where('slug', $slug)->get();
        if (count($post)) { return $post[0]; }

        return [];
    }

    public function getThumbnailAttribute()
    {
        return env('AWS_PUBLIC_LINK').$this->attributes['thumbnail'];
    }
}
