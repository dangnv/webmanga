@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1 class="search-with-google">
            <a href="https://www.google.com/search?q={{ $key_word }}" class="is-link" target="_blank" rel="nofollow">Search for <strong>{{ $key_word }}</strong> on Google</a>
        </h1>
    </div>
    @include('includes._lst_posts', ['posts' => $posts])
    @include('includes.__paginate', [
        'current_page'  => $current_page,
        'total_pages'   => $total_pages,
        'is_night_mode' => $is_night_mode,
        'route_name'    => 'post.search',
        'key_word'      => $key_word
    ])
@endsection
@section('title')
    {{ !empty($key_word) ? $key_word : 'Search' }}
@endsection
@section('css_files')
    <style>
        .search-with-google {
            font-size: 1.5rem;
        }
    </style>
@endsection
