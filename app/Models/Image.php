<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $fillable = ['url', 'chapter_id'];

    public function chapter () {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }
}
