<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        /** Get on going posts */
        $onGoingPosts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                            ->where('status', Post::STATUS_ON_GOING)
                            ->orderBy('published_date', 'desc')
                            ->take(20)
                            ->get();

        /** Get completed posts */
        $completedPosts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
            ->where('status', Post::STATUS_COMPLETED)
            ->orderBy('published_date', 'desc')
            ->take(20)
            ->get();

        return $this->renderView($request, 'home.index', [
            'on_going_posts'    => $onGoingPosts,
            'completed_posts'   => $completedPosts
        ]);
    }
}
