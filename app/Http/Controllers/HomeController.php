<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\RedirectResponse;
use App\Models\Tag;

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
            'completed_posts'   => $completedPosts,
            'is_show_search'    => true
        ]);
    }

    public function postByTags (Request $request)
    {
        $slug = $request->get('q');

        if (empty($slug)) {
            return redirect(route('home'));
        }
        $itemPerPage = Post::ITEM_PER_PAGE;
        $page = $request->get('page') ?? Post::CURRENT_PAGE;
        $limit = $itemPerPage * ($page - 1);
        $tag = Tag::select('id', 'name')->where('slug', $slug)->get();
        if (!count($tag)) {
            $posts = [];
            $totalPages = 0;
        } else {
            $tag = $tag[0];
            $totalPages = ceil(Post::whereIn('id', PostTag::select('post_id')->where('tag_id', $tag->id))->count() / $itemPerPage);

            $posts = Post::select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.status',
                'posts.is_new',
                'posts.thumbnail',
                DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                ->whereIn('id', PostTag::select('post_id')->where('tag_id', $tag->id))
                ->orderBy('published_date', 'desc')
                ->skip($limit)
                ->take($itemPerPage)
                ->get();
            $slug = $tag->name;
        }

        return $this->renderView($request, 'post.search', [
            'is_show_tags'  => false,
            'posts'         => $posts,
            'total_pages'   => $totalPages,
            'current_page'  => $page,
            'is_show_search'=> true,
            'key_word'      => $slug
        ]);
    }

    public function search(Request $request) {
        $keyWord = $request->get('q');
        if (empty($keyWord)) {
            return redirect(route('home'));
        }
        $itemPerPage = Post::ITEM_PER_PAGE;
        $page = $request->get('page') ?? Post::CURRENT_PAGE;
        $limit = $itemPerPage * ($page - 1);
        $totalPages = ceil(Post::where('title', 'LIKE', $keyWord)
                                    ->orWhere(DB::raw('(select title from chapters where post_id = posts.id LIMIT 1)'), 'LIKE', "%{$keyWord}%")
                                    ->count() / $itemPerPage);
        $posts = Post::select(
            'posts.id',
            'posts.title',
            'posts.slug',
            'posts.status',
            'posts.is_new',
            'posts.thumbnail',
            DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
            ->where('title', 'LIKE', "%{$keyWord}%")
            ->orWhere(DB::raw('(select title from chapters where post_id = posts.id LIMIT 1)'), 'LIKE', "%{$keyWord}%")
            ->orderBy('published_date', 'desc')
            ->skip($limit)
            ->take($itemPerPage)
            ->get();

        return $this->renderView($request, 'post.search', [
            'is_show_tags'  => false,
            'posts'         => $posts,
            'total_pages'   => $totalPages,
            'current_page'  => $page,
            'is_show_search'=> true,
            'key_word'      => $keyWord
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
            $totalPages = ceil(Post::where('status', Post::STATUS_ON_GOING)->count() / $itemPerPage);

            $posts = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                        ->where('status', Post::STATUS_ON_GOING)
                        ->orderBy('published_date', 'desc')
                        ->skip($limit)
                        ->take($itemPerPage)
                        ->get();
            return $this->renderView($request, 'post.latest', [
                'is_show_tags'  => false,
                'posts'         => $posts,
                'total_pages'   => $totalPages,
                'current_page'  => $page,
                'is_show_search'=> true
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.latest', [
                'is_show_tags'      => false,
                'is_show_search'    => true
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
                'current_page'  => $page,
                'is_show_search'=> true
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.completed', [
                'is_show_tags'      => false,
                'is_show_search'    => true
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
                'current_page'  => $page,
                'is_show_search'=> true
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.newest', [
                'is_show_tags'      => false,
                'is_show_search'    => true
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
                'current_page'  => $page,
                'is_show_search'=> true
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.all', [
                'is_show_tags'      => false,
                'is_show_search'    => true
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
                'is_show_search'=> true,
                'is_show_popular_posts'  => $is_show_popular_posts,
            ]);
        } catch (\Exception $exception) {
            Log::error("Exception: {$exception->getMessage()}");
            return view('post.category', [
                'is_show_tags'      => false,
                'is_show_search'    => true
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
            $this->renderView($request, 'news.index', [
                'is_show_categories'    => false,
                'is_show_tags'          => false
            ]);
        }
    }

    /**
     * @param $slug
     * @param Request $request
     * @return View
     */
    public function detailPost ($slug, Request $request)
    {
        $post = Post::getPostBySlug($slug);
        $data = [
            'is_show_popular_posts' => false,
            'is_show_tags' => false,
            'post' => $post
        ];

        if (empty($post)) {
            $data['recommend_posts'] = Post::select('*', DB::raw('(select published_date from chapters where post_id = posts.id LIMIT 1) as published_date'))
                ->orderBy('published_date', 'desc')
                ->take(20)
                ->get();
        } else {
            $post->views = $post->views + 1;
            $post->save();
        }

        return $this->renderView($request, 'post.detail', $data);
    }

    public function postComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'post_id' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'comment');
        }

        $data = $request->all();
        unset($data['_token']);

        try {
            Comment::create($data);

            return redirect()->back();
        } catch (\Exception $exception) {
            Log::error("Exception post comment: {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function postBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'bookmark');
        }

        $data = $request->all();
        unset($data['_token']);

        try {
            Bookmark::create($data);

            return redirect()->back();
        } catch (\Exception $exception) {
            Log::error("Exception post bookmark: {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeBookmark(Request $request) {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'bookmark');
        }

        try {
            Bookmark::where('post_id', Post::select('id')->where('slug', $request->post_slug)->pluck('id'))
                    ->where('user_id', Auth::id())
                    ->delete();

            return redirect()->back();
        } catch (\Exception $exception) {
            Log::error("Exception post bookmark: {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeAllBookmark(Request $request)
    {
        try {
            Bookmark::where('user_id', Auth::id())->delete();

            return redirect()->back();
        } catch (\Exception $exception) {
            Log::error("Exception post bookmark: {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function detailChapter($post_slug, $chapter_slug, Request $request)
    {
        $post = Post::getPostBySlug($post_slug);
        if (!empty($post)) {
            $chapter = Chapter::getChapterBySlug($chapter_slug, $post->id);
            if (!empty($chapter) && $chapter->post_id == $post->id) {
                $images = Image::where('chapter_id', $chapter->id)->get();

                return $this->renderView($request, 'post.chapter.detail', [
                    'images' => $images,
                    'chapter' => $chapter,
                    'post' => $post,
                    'is_show_tags' => false,
                    'is_show_popular_posts' => false,
                    'is_show_categories' => false,
                ]);
            }
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return View
     */
    public function profile(Request $request)
    {
        $bookmarkPosts = Bookmark::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return $this->renderView($request, 'profile.index', [
            'user' => Auth::user(),
            'bookmark_posts' => $bookmarkPosts,
            'is_show_tags' => false,
            'is_show_popular_posts' => false,
            'is_show_categories' => false,
        ]);
    }

    public function newsDetail($slug, Request $request)
    {
        $article = Article::getArticleBySlug($slug);
        if (!empty($article)) {
            $tagIds = ArticleTag::select('tag_id')->where('article_id', $article->id)->pluck('tag_id');
            $articleIds = ArticleTag::select('article_id')->whereIn('tag_id', $tagIds)->pluck('article_id');
            $newsRecommend = Article::whereIn('id', $articleIds)->whereNotIn('id', [$article->id])->get();
        } else {
            $newsRecommend = Article::take(Article::ITEM_PER_PAGE)->orderBy('public_at', 'desc')->get();
        }

        return $this->renderView($request, 'news.detail', [
            'article' => $article,
            'newsRecommend' => $newsRecommend,
            'is_show_tags' => false,
            'is_show_categories' => false
        ]);
    }

    /**
     * Footer menu
     */
    public function about(Request $request)
    {
        return $this->renderView($request, 'home.about', [
            'is_show_popular_posts' => false,
            'is_show_tags' => false,
            'is_show_categories' => false
        ]);
    }
    public function term(Request $request)
    {
        return $this->renderView($request, 'home.term', [
            'is_show_popular_posts' => false,
            'is_show_tags' => false,
            'is_show_categories' => false
        ]);
    }
    public function privacy(Request $request)
    {
        return $this->renderView($request, 'home.privacy', [
            'is_show_popular_posts' => false,
            'is_show_tags' => false,
            'is_show_categories' => false
        ]);
    }
}
