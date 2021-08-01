@extends('base.layout_base')
@section('content-page')
    <div class="row col-12 content-profile card" style="width: 18rem;">
        <div class="card-header">
            <div class="card-avatar">
                <img class="avatar" src="{{ $user->avatar }}" alt="Card image cap">
            </div>
            <div class="card-user-info">
                <p>{{ $user->name }}</p>
                <p>Joined at {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="card-body">
            <div class="lst-bookmarks">
                <strong>Bookmarks</strong>
                <br>
                <span>{{ count($user->bookmark) }}</span>
            </div>
            <div class="lst-comments">
                <strong>Comments</strong>
                <br>
                <span>{{ count($user->comments) }}</span>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="#"><i class="fa fa-bookmark-o" aria-hidden="true"></i> Bookmark</a></li>
            <li class="list-group-item"><a href="#" onclick="$('#form-logout').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{ trans('text.menu.btn_logout') }}</a></li>
        </ul>
    </div>
@endsection
@section('title')
    {{ trans('text.latest.title') }}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/profile/index.css') }}?v={{ env('STATIC_VER', '20210726') }}">
@endsection
