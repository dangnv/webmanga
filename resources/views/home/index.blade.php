@extends('base.layout_base')
@section('content-page')
    <div class="row col-12">
        <h1>{{ trans('text.home.lbl_title_on_going') }}</h1>
    </div>
    @include('includes._lst_posts', ['posts' => $on_going_posts])
    <div class="row col-12">
        <a href="{{ route(($is_night_mode ? 'night.' : '').'post.latest') }}" class="form-control btn btn-primary btn-show-more">{{ trans('text.global.btn_see_more') }}</a>
    </div>

    <div class="row col-12">
        <h1>{{ trans('text.home.lbl_title_completed') }}</h1>
    </div>
    @include('includes._lst_posts', ['posts' => $completed_posts])
    <div class="row col-12">
        <a href="{{ route(($is_night_mode ? 'night.' : '').'post.completed') }}" class="form-control btn btn-primary btn-show-more">{{ trans('text.global.btn_see_more') }}</a>
    </div>
@endsection
@section('title')
    {{ trans('text.home.title') }}
@endsection
