@extends('base.layout_base')
@section('content-page')
    <div class="content-profile card">
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
            <li class="list-group-item"><a href="#" onclick="$('#form-logout').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> {{ trans('text.menu.btn_logout') }}</a></li>
        </ul>
    </div>
    <div class="lst-bookmarks-user">
        <h2 class="title is-5">Bookmark</h2>
        @if (count($bookmark_posts))
            @foreach($bookmark_posts as $item)
                <div class="bookmark-post">
                    <a href="{{ route(($is_night_mode ? 'night.' : '').'post.detail', ['slug' => $item->post->slug]) }}" class="image">
                        <img src="{{ $item->post->thumbnail }}" />
                    </a>
                    <div class="bookmark-post-info">
                        <a href="{{ route(($is_night_mode ? 'night.' : '').'post.detail', ['slug' => $item->post->slug]) }}"><h3>{{ $item->post->title }}</h3></a>
                        <p>{{ \Carbon\Carbon::parse($item->created_at)->format('M d Y') }}</p>
                    </div>
                    <div class="bookmark-post-remove">
                        <i onclick="$('#form-remove-bookmark-{{ $item->post->slug }}').submit()" class="fa fa-trash-o fa-4" aria-hidden="true"></i>
                    </div>
                    <form action="{{ route('post.bookmark.remove') }}" method="post" id="form-remove-bookmark-{{ $item->post->slug }}">
                        @csrf
                        <input type="hidden" name="post_slug" value="{{ $item->post->slug }}">
                    </form>
                </div>
            @endforeach
            <div class="btn-clear-all-bookmark">
                <a class="form-control btn btn-primary btn-clear-all" onclick="$('#form-remove-bookmar-all').submit()"><i class="fa fa-trash-o fa-4" aria-hidden="true"></i> Clear all</a>
                <form action="{{ route('post.bookmark.remove.all') }}" method="post" id="form-remove-bookmar-all">
                    @csrf
                </form>
            </div>
        @else
            <span class="no-bookmark">There is no bookmark.</span>
        @endif
    </div>
@endsection
@section('title')
    {{ $user->name }}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/profile/index.css') }}?v={{ time() }}">
@endsection
