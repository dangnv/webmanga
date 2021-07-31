@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb article-lst">
                <li class="breadcrumb-item"><a href="{{ route(($is_night_mode ? 'night.' : '').'home') }}">{{ env('APP_TITLE_PAGE') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">News</li>
            </ol>
        </nav>
    </div>
    <div class="row col-12">
        <h1>{{ trans('text.news.title') }}</h1>
    </div>
    @foreach($news as $item)
        <a href="#" class="row box-news">
            <div class="image">
                <img src="{{ $item->thumbnail }}">
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8 comtent-news">
                <div class="media-content">
                    <div class="content">
                        <div class="mtitle">
                            {{ $item->title }}
                        </div>
                        <p class="msub">{{ $item->public_at ? \Carbon\Carbon::create($item->public_at)->format('M d Y') : '' }}</p>
                    </div>
                    <div class="ellipsis is-ellipsis-1 is-fixed-bottom">
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

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
