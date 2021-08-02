<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmarks';
    protected $fillable = ['user_id', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public static function bookmarkDone($postId, $userId)
    {
        return self::where('post_id', $postId)
                    ->where('user_id', $userId)
                    ->count();
    }
}
