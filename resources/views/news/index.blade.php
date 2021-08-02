@extends('base.layout_base')
@section('content-page')
    @include('includes.news._breadcrumb')
    <div class="row col-12">
        <h1>{{ trans('text.news.title') }}</h1>
    </div>
    @include('includes.news._lst_news', ['news' => $news])

    @include('includes.__paginate', [
        'current_page'  => $current_page,
        'total_pages'   => $total_pages,
        'is_night_mode' => $is_night_mode,
        'route_name'    => 'news.index'
    ])
@endsection
@section('title')
    {{ trans('text.news.title') }}
@endsection
