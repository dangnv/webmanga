@extends('base.layout_base')
@section('content-page')
    @if (!empty($article))
    @include('includes.news._breadcrumb', ['isNewsDetail' => true, $article])
    <div class="content box-news-detail">
        {!! $article->content !!}
    </div>
    @else
        @include('includes._not_found', ['show_recomment_text' => false])
    @endif

    @if (count($newsRecommend))
    <div class="box-list-news-recommend">
        <h2>{{ trans('text.category.recommend') }}</h2>
    </div>

    @include('includes.news._lst_news', ['news' => $newsRecommend])
    @endif
@endsection
@section('title')
    {{ !empty($article) ? $article->title : 'Article detail' }}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/news/detail.css') }}?v={{ time() }}">
@endsection
