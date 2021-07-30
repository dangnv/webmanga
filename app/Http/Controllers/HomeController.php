<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Contracts\View\View;

class HomeController extends BaseController
{
    /**
     * @param Request $request
     * @return View
     */
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

    /**
     * @param Request $request
     * @return View
     */
    public function latest(Request $request)
    {
        try {
            $itemPerPage = Post::ITEM_PER_PAGE;
            $page = $request->get('page') ?? Post::CURRENT_PAGE;
            $limit = $itemPerPage * ($page - 1);
            $totalPages = ceil(Post::count() / $itemPerPage);

            $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                        ->orderBy('published_date', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'post.latest', [
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.latest', []);
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function completed(Request $request)
    {
        try {
            $itemPerPage = Post::ITEM_PER_PAGE;
            $page = $request->get('page') ?? Post::CURRENT_PAGE;
            $limit = $itemPerPage * ($page - 1);
            $totalPages = ceil(Post::where('status', Post::STATUS_COMPLETED)->count() / $itemPerPage);

            $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                        ->where('status', Post::STATUS_COMPLETED)
                        ->orderBy('published_date', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'post.completed', [
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.completed', []);
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function newest(Request $request)
    {
        try {
            $itemPerPage = Post::ITEM_PER_PAGE;
            $page = $request->get('page') ?? Post::CURRENT_PAGE;
            $limit = $itemPerPage * ($page - 1);
            $totalPages = ceil(Post::where('is_new', Post::STATUS_NEW)->count() / $itemPerPage);

            $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                        ->where('is_new', Post::STATUS_NEW)
                        ->orderBy('published_date', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'post.newest', [
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.newest', []);
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function all(Request $request)
    {
        try {
            $itemPerPage = Post::ITEM_PER_PAGE;
            $page = $request->get('page') ?? Post::CURRENT_PAGE;
            $limit = $itemPerPage * ($page - 1);
            $totalPages = ceil(Post::count() / $itemPerPage);

            $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                        ->orderBy('published_date', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'post.all', [
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.all', []);
        }
    }
}
