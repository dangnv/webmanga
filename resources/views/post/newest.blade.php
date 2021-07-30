@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1>{{ trans('text.newest.title') }}</h1>
    </div>
    @include('includes._lst_posts', ['posts' => $posts])
    @include('includes.__paginate', [
        'current_page'  => $current_page,
        'total_pages'   => $total_pages,
        'is_night_mode' => $is_night_mode,
        'route_name'    => 'post.newest'
    ])
@endsection
@section('title')
    {{ trans('text.newest.title') }}
@endsection
