@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1>{{ trans('text.home.lbl_title_on_going') }}</h1>
    </div>
    @foreach($on_going_posts as $post)
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
    <div class="row col-12">
        <button class="form-control btn btn-primary btn-show-more">{{ trans('text.global.btn_see_more') }}</button>
    </div>

    <div class="row col-12">
        <h1>{{ trans('text.home.lbl_title_completed') }}</h1>
    </div>
    @foreach($completed_posts as $post)
        <a href="#" class="row col-xs-12 col-sm-12 col-md-12 col-lg-6 box-on-going">
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
        </a>
    @endforeach
    <div class="row col-12">
        <button class="form-control btn btn-primary btn-show-more">{{ trans('text.global.btn_see_more') }}</button>
    </div>
@endsection
@section('title')
    {{ trans('text.home.title') }}
@endsection
