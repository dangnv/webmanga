<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['user_id', 'post_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * @param $userId
     * @param $postId
     * @return mixed
     */
    public static function getLstComments ($userId, $postId)
    {
        return self::where('user_id', $userId)
                    ->where('post_id', $postId)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}
