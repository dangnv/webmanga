@extends('base.layout_base')
@section('content-page')
    @if (empty($category))
        <div class="row not-found-category">
            <h3 class="title is-4 boxed-section-title has-text-centered">
                Whoops, 404 – Sorry, this page can't be found.
            </h3>
            <p class="has-text-centered">It seems that page you are looking for no longer exists.</p>
        </div>
        <div class="row col-12">
            <h1>{{ trans('text.category.recommend') }}</h1>
        </div>
    @else
        <div class="row col-12">
            <h1>{{ trans('text.category.title') }}</h1>
        </div>
    @endif
    @include('includes._lst_posts', ['posts' => $posts])
    @include('includes.__paginate', [
        'current_page'  => $current_page,
        'total_pages'   => $total_pages,
        'is_night_mode' => $is_night_mode,
        'category'      => $category,
        'route_name'    => 'post.category'
    ])
@endsection
@section('title')
    {{ trans('text.category.title') }}
@endsection
