@extends('base.layout_base')
@section('content-page')
    @if (!empty($images))
        <div class="row col-12 box-chapter-title">
            <h2>{!! $post->title !!}</h2>
            <h3>{!! $chapter->title !!}</h3>
        </div>
        @include('includes.chapter._button', ['post' => $post, 'chapter' => $chapter])
        <div class="row col-12 box-list-images">
            @foreach($images as $key => $image)
                <div class="image-chapter">
                    @php $url = route('image.get', ['post_slug' => $post->slug, 'chapter_slug' => $chapter->slug, 'image_id' => $image->id]) @endphp
                    <img @if ($key > 3) class="lazy" src="{{ asset('images/loading2.gif') }}" @else src="{{ $url }}" @endif data-src="{{ $url }}" onerror="this.src='{{ asset('images/loading2.gif') }}'"/>
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
    {!! !empty($chapter) ? $chapter->title : 'Chapter detail' !!}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/post/chapter.css') }}?v={{ time() }}">
@endsection
@section('js_files')
    <script src="{{ asset('js/chapter.js') }}?v={{ time() }}"></script>
@endsection
