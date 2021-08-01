@extends('base.layout_base')
@section('content-page')
    @if (!empty($images))
        <div class="row col-12 box-chapter-title">
            <h2>{{ $post->title }}</h2>
            <h3>{{ $chapter->title }}</h3>
        </div>
        @include('includes.chapter._button', ['post' => $post])
        <div class="row col-12 box-list-images">
            @foreach($images as $image)
                <div class="image-chapter">
                    <img src="{{ $image->url }}"/>
                </div>
            @endforeach
        </div>
        @include('includes.chapter._button', ['post' => $post])
    @else
        @include('includes._not_found')
        @include('includes._lst_posts', ['posts' => $recommend_posts])
    @endif
@endsection
@section('title')
    {{ trans('text.chapter.title') }}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/post/chapter.css') }}?v={{ env('STATIC_VER', '20210726') }}">
@endsection
