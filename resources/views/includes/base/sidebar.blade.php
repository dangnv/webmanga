@if (isset($tags))
<div class="row sidebar-keywords">
    <div class="row col-12">
        <h2>{{ trans('text.home.lbl_key') }}</h2>
    </div>
    <div class="col-12">
        @foreach($tags as $keyWord)
            <a class="badge badge-success" href="#search">{{ $keyWord->name }}</a>
        @endforeach
    </div>
</div>
@endif
@if (isset($categories))
<div class="row sidebar-categories">
    <div class="row">
        <h2>{{ trans('text.home.lbl_genres') }}</h2>
    </div>
    <div class="row">
        @foreach($categories as $cate)
            <div class="col-6">
                <a class="btn {{ $is_night_mode ? 'btn-dark' : 'btn-light' }}" href="{{ route('post.category', ['slug' => $cate->slug]) }}">{{ $cate->name }}</a>
            </div>
        @endforeach
    </div>
</div>
@endif
@if (isset($popular_posts))
<div class="row sidebar-popular-posts">
    <div class="row col-12">
        <h2>{{ trans('text.home.lbl_popular_post') }}</h2>
    </div>
    @foreach($popular_posts as $post)
        <a href="{{ route(($is_night_mode ? 'night.' : '').'post.detail', ['slug' => $post->slug]) }}" class="row col-12 box-post-detail">
            <div class="image">
                <img src="{{ $post->thumbnail }}">
            </div>
            <div class="col-8">
                <div class="media-content">
                    <div class="content">
                        <div class="mtitle">{{ $post->title }}</div>
                        <p class="msub">
                            Views: {{ number_format($post->views) }}
                            · {{ $post->published_date ? \Carbon\Carbon::create($post->published_date)->format('M d Y') : '' }}</p>
                        <span class="ellipsis is-ellipsis-1">
                            @php $lastChapter = \App\Models\Post::getLastChapter($post->id) @endphp
                            Last chapter: <span style="font-weight: 500;">{{ count($lastChapter) > 0 ? $lastChapter[0]->title : '' }}</span>
                        </span>
                    </div>
                    <div class="ellipsis is-ellipsis-1 is-fixed-bottom">
                        @if ($post->categories)
                            @foreach($post->categories as $cate)
                            <span class="tag is-light is-small">{{ $cate->category ? $cate->category->name : '' }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endif
