<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
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
            'is_home'           => true,
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
                'is_show_tags'  => false,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.latest', [
                'is_show_tags' => false
            ]);
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
                'is_show_tags'  => false,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.completed', [
                'is_show_tags'  => false,
            ]);
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
                'is_show_tags'  => false,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.newest', [
                'is_show_tags'  => false
            ]);
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
                'is_show_tags'  => false,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.all', [
                'is_show_tags'  => false
            ]);
        }
    }

    /**
     * @param Request $request
     * @param string $category
     * @return View
     */
    public function postByCategory($slug, Request $request)
    {
        try {
            $category = Category::where('slug', $slug)->get();
            $is_show_popular_posts = true;
            if (!count($category)) {
                $itemPerPage = 20;
                $totalPages  = 0;
                $page        = 0;
                $category    = [];
                $is_show_popular_posts = false;
                $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                    ->whereIn('id', PostCategory::select('post_id')->where('category_id', DB::raw('(select id from categories LIMIT 1)'))->pluck('post_id'))
                    ->orderBy('published_date', 'desc')
                    ->take($itemPerPage)
                    ->get();
            } else {
                $category = $category[0];
                $itemPerPage = Post::ITEM_PER_PAGE;
                $page = $request->get('page') ?? Post::CURRENT_PAGE;
                $limit = $itemPerPage * ($page - 1);
                $totalPages = ceil(Post::whereIn('id', PostCategory::select('post_id')->where('category_id', $category->id)->pluck('post_id'))->count() / $itemPerPage);

                $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                    ->whereIn('id', PostCategory::select('post_id')->where('category_id', $category->id)->pluck('post_id'))
                    ->orderBy('published_date', 'desc')
                    ->skip($limit)
                    ->take($itemPerPage)
                    ->get();
            }
            return $this->renderView($request, 'post.category', [
                'is_show_tags'  => false,
                'category'      => $category,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page,
                'is_show_popular_posts'  => $is_show_popular_posts,
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.category', [
                'is_show_tags'  => false
            ]);
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function news(Request $request)
    {
        try {
            $itemPerPage = Article::ITEM_PER_PAGE;
            $page = $request->get('page') ?? Article::CURRENT_PAGE;
            $limit = $itemPerPage * ($page - 1);
            $totalPages = ceil(Article::count() / $itemPerPage);

            $news = Article::select('*')
                        ->orderBy('public_at', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'news.index', [
                'is_show_categories'    => false,
                'is_show_tags'          => false,
                'news'                  => $news,
                'total_pages'           => $totalPages,
                'current_page'          => $page
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('news.index', [
                'is_show_categories'    => false,
                'is_show_tags'          => false
            ]);
        }
    }
}
