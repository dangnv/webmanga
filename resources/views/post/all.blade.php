@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1>{{ trans('text.all.title') }}</h1>
    </div>
    @include('includes._lst_posts', ['posts' => $posts])
    @include('includes.__paginate', [
        'current_page'  => $current_page,
        'total_pages'   => $total_pages,
        'is_night_mode' => $is_night_mode,
        'route_name'    => 'post.all'
    ])
@endsection
@section('title')
    {{ trans('text.all.title') }}
@endsection
