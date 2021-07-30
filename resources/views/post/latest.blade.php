@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1>{{ trans('text.latest.title') }}</h1>
    </div>
    @foreach($posts as $post)
        <div href="#" class="row col-xs-12 col-sm-12 col-md-12 col-lg-6 box-on-going">
            <div class="image">
                <img src="{{ $post->thumbnail }}">
            </div>
            <div class="col-8">
                <div class="media-content">
                    <div class="content">
                        <div class="mtitle">
                            {{ $post->title }}
                        </div>
                        <p class="msub">
                            {{ ($post->status == \App\Models\Post::STATUS_ON_GOING) ? trans('text.home.lbl_status_on_going') : trans('text.home.lbl_status_completed') }}
                            · {{ $post->published_date ? \Carbon\Carbon::create($post->published_date)->format('M d Y') : '' }}</p>
                        <span class="ellipsis is-ellipsis-1">
                            @php $lastChapter = \App\Models\Post::getLastChapter($post->id) @endphp
                            Last chapter: <span style="font-weight: 500;">{{ count($lastChapter) > 0  ? $lastChapter[0]->title : '' }}</span>
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
        </div>
    @endforeach
    <div class="row col-12 box-navigation">
        <nav aria-label="Page navigation example">
            <ul class="pagination pagination-md">
                @php
                    $start = ($current_page < 4) ? 1 : ($current_page - 2);
                    if ($start + 4 > $total_pages) {
                        $start = $total_pages - 3;
                    }
                    $previous = ($current_page <= 1) ? 1 : ($current_page - 1);
                    $next = ($current_page >= $total_pages) ? $total_pages : ($current_page +1);
                @endphp
                <li class="page-item {{ $current_page <= 1 ? 'disabled' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').'post.latest', ['page' => $previous]) }}"> < </a></li>
                @for ($page = $start; $page < ($start + 4); $page++)
                    @if ($page <= $total_pages)
                        <li class="page-item {{ $page == $current_page ? 'active' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').'post.latest', ['page' => $page]) }}">{{ $page }}</a></li>
                    @endif
                @endfor
                <li class="page-item {{ $current_page >= $total_pages ? 'disabled' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').'post.latest', ['page' => $next]) }}"> > </a></li>
            </ul>
        </nav>
    </div>
@endsection
@section('title')
    {{ trans('text.latest.title') }}
@endsection
