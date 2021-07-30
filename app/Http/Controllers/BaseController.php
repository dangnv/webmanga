<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    public function renderView($request, $viewer, $data = [])
    {
        /** Get list tags for keywords box */
        if (!isset($data['is_show_tags']) || $data['is_show_tags']) {
            $tags = Tag::orderBy(DB::raw('(select count(*) from post_tags where tag_id = tags.id)'), 'desc')->get();
            $data['tags'] = $tags;
        }

        /** Get popular posts */
        if (!isset($data['is_show_popular_posts']) || $data['is_show_popular_posts']) {
            $popularPosts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                ->orderBy('views', 'desc')
                ->orderBy('published_date', 'desc')
                ->take(10)
                ->get();
            $data['popular_posts'] = $popularPosts;
        }
        /** Get categories */
        if (!isset($data['is_show_categories']) || $data['is_show_categories']) {
            $categories = Category::orderBy('name', 'asc')->get();
            $data['categories'] = $categories;
        }

        $data['is_night_mode'] = count(explode('night-mode', $request->path())) > 1;

        return view($viewer, $data);
    }
}
