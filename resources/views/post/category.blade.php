@extends('base.layout_base')
@section('content-page')
    @if (empty($category))
        @include('includes._not_found')
    @else
        <div class="row col-12">
            <h1>{{ trans('text.category.title', ['category' => $category->name]) }}</h1>
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
    {{ !empty($category) ? trans('text.category.title', ['category' => $category->name]) : trans('text.global.lbl_not_found') }}
@endsection
