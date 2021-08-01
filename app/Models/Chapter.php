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
}
