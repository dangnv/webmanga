<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $fillable = ['thumbnail', 'title', 'slug', 'description', 'content', 'public_at'];

    const ITEM_PER_PAGE = 10;
    const CURRENT_PAGE  = 1;
}
