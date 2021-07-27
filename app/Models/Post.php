<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['id', 'thumbnail', 'title', 'slug', 'status', 'is_new', 'alt_names', 'author', 'artist', 'demographic', 'format', 'description', 'views'];

    const STATUS_ON_GOING   = 0;
    const STATUS_COMPLETED  = 1;

    const STATUS_NEW        = 1;
    const STATUS_NOT_NEW    = 0;

    public function tags()
    {
        return $this->hasMany(PostTag::class, 'post_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(PostCategory::class, 'post_id', 'id');
    }

    public static function getLastChapter($id)
    {
        return Chapter::select('*')
                        ->where('post_id', $id)
                        ->orderBy('published_date', 'desc')
                        ->take(1)
                        ->get();
    }
}
