@extends('base.layout_base')
@section('content-page')
    @if (isset($errors) && $errors->comment && $errors->comment->first())
        <script>
            swal({
                text: "{{ $errors->comment->first() }}",
                dangerMode: true,
            });
        </script>
    @endif
    @if (isset($errors) && $errors->bookmark && $errors->bookmark->first())
        <script>
            swal({
                text: "{{ $errors->bookmark->first() }}",
                dangerMode: true,
            });
        </script>
    @endif
    @if (!empty($post))
        <div class="row col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb article-lst">
                    <li class="breadcrumb-item"><a href="{{ route(($is_night_mode ? 'night.' : '').'home') }}">{{ env('APP_TITLE_PAGE') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="row col-12 post-title-detail">
            <div class="box-info">
                <div class="image">
                    <img class="thumbnail" src="{{ $post->thumbnail }}" />
                </div>
                <div class="post-title">
                    <h1 class="title is-4 mb-0">{{ $post->title }}</h1>
                    @if (count($post->chapters))
                    <p>
                        Last chapter: <a href="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $post->chapters[0]->slug]) }}">{{ $post->chapters[0]->title }}</a>
                    </p>
                    @endif
                </div>
            </div>
            <div class="box-share-button">
                <div class="row">
                    @if (count($post->chapters))
                        <a href="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $post->chapters[0]->slug]) }}"
                    @else <a href="#"
                    @endif class="btn btn-danger"><i class="fa fa-play" aria-hidden="true"></i> Read now</a>
                    @if (\Illuminate\Support\Facades\Auth::check())
                        @php $user = \Illuminate\Support\Facades\Auth::user() @endphp
                        @if (\App\Models\Bookmark::bookmarkDone($post->id, $user->id))
                            <a href="#" onclick="$('#form-remove-bookmark').submit()" class="btn btn-outline-warning"><i class="fa fa-bookmark-o" aria-hidden="true"></i> Remove bookmark</a>
                            <form action="{{ route('post.bookmark.remove') }}" method="post" id="form-remove-bookmark">
                                @csrf
                                <input type="hidden" name="post_slug" value="{{ $post->slug }}">
                            </form>
                        @else
                            <a href="#" onclick="$('#form-bookmark').submit()" class="btn btn-success"><i class="fa fa-bookmark-o" aria-hidden="true"></i> Bookmark</a>
                            <form action="{{ route('bookmark.post') }}" method="post" id="form-bookmark">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                            </form>
                        @endif
                    @else
                        <a href="#menu" onclick="swal('Login to bookmark for this manga!');" class="btn btn-success"><i class="fa fa-bookmark-o" aria-hidden="true"></i> Bookmark</a>
                    @endif
                </div>
                <div class="row box-socials-share">
                    <div class="socials-share">
                        @php $urlToShare = route(($is_night_mode ? 'night.' : '').'post.detail', ['slug' => $post->slug]); @endphp
                        <a class="bg-facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ $urlToShare }}" target="_blank"><span class="fa fa-facebook"></span></a>
                        <a class="bg-twitter" href="https://twitter.com/share?text={{ $post->title }}&url={{ $urlToShare }}" target="_blank"><span class="fa fa-twitter"></span></a>
                        <a class="bg-google-plus" href="https://plus.google.com/share?url={{ $urlToShare }}" target="_blank"><span class="fa fa-google-plus"></span></a>
                        <a class="bg-pinterest" href="https://www.pinterest.com/pin/create/button/?url={{ $urlToShare }}&media={{ $post->thumbnail }}&description={{ $post->title }}" target="_blank"><span class="fa fa-pinterest"></span></a>
                        <a class="bg-email" href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to&su={{ $post->title }}&body={{ $urlToShare }}" target="_blank"><span class="fa fa-envelope"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-12 post-overview">
            <h2 class="title is-5 mt-4 mb-2">Overview</h2>
            <table class="table">
                <tbody>
                <tr>
                    <th scope="row">Alt names:</th>
                    <td>{{ $post->alt_names }}</td>
                </tr>
                <tr>
                    <th scope="row">Author:</th>
                    <td>{{ $post->author }}</td>
                </tr>
                <tr>
                    <th scope="row">Artist:</th>
                    <td>{{ $post->artist }}</td>
                </tr>
                <tr>
                    <th scope="row">Genre:</th>
                    <td>
                        @if ($post->categories)
                            @foreach($post->categories as $cate)
                                <span class="badge badge-info">{{ $cate->category->name }}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <th scope="row">Format:</th>
                    <td>{{ $post->format }}</td>
                </tr>
                <tr>
                    <th scope="row">Demographic:</th>
                    <td>{{ $post->demographic }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row col-12 post-chapters-lst">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-chapters-lst" role="tab" aria-controls="nav-home" aria-selected="true">Chapter list</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-description" role="tab" aria-controls="nav-profile" aria-selected="false">Description</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-chapters-lst" role="tabpanel" aria-labelledby="nav-home-tab">
                    <table class="table table-chapters">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Published date</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($post->chapters as $key => $chapter)
                        <tr class="{{ $key > \App\Models\Chapter::ITEM_PER_PAGE ? 'hide' : '' }}">
                            <td scope="row"><a href="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $chapter->slug]) }}">{{ $chapter->title }}</a></td>
                            <td>{{ $chapter->published_date ? \Carbon\Carbon::create($chapter->published_date)->format('Y/m/d') : '' }}</td>
                            <td><a href="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $chapter->slug]) }}">Read</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if (count($post->chapters) > \App\Models\Chapter::ITEM_PER_PAGE)
                    <div class="row col-12">
                        <a onclick="$('#nav-chapters-lst tr.hide').show(); $(this).hide();" class="form-control btn btn-primary btn-show-more">{{ trans('text.global.btn_see_more') }}</a>
                    </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="nav-description" role="tabpanel" aria-labelledby="nav-profile-tab">
                    {!! $post->description !!}
                </div>
            </div>
        </div>

        <div class="row col-12 post-comments">
            <h2 class="title is-5 mt-4 mb-2">Comments</h2>
            @if (!count($post->comments))
                <p class="no-comments">No comment!</p>
            @else
                @foreach($post->comments as $comment)
                    <div class="row box-comment">
                        <img src="{{ $comment->user->avatar }}">
                        <div>
                            <span>{{ $comment->user->name }}</span>
                            <br>
                            <span>{{ $comment->content }}</span>
                            <br>
                            <small>{{ \Carbon\Carbon::parse($comment->created_at)->format('Y-m-d H:i:s') }}</small>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (\Illuminate\Support\Facades\Auth::check())
                <hr>
                <form id="form-post-comment" action="{{ route('comment.post') }}" method="post">
                    @csrf
                    <div class="row">
                        <label for="comment-ipt">
                            <img src="{{ \Illuminate\Support\Facades\Auth::user()->avatar }}"/>
                        </label>
                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::user()->id }}">
                        <input type="hidden" name="post_id" value="{{ $post->id }}" >
                        <div class="col-10">
                            <textarea class="form-control" id="comment-ipt" placeholder="Add a comment..." name="content" minlength="10" maxlength="5000"></textarea>
                            <button type="submit" class="btn btn-primary">Post comment</button>
                        </div>
                    </div>
                </form>
            @else
                <p class="comment-not-auth"><a href="#menu">Login</a> to comment for this manga!</p>
            @endif
        </div>
    @else
        @include('includes._not_found')
        @include('includes._lst_posts', ['posts' => $recommend_posts])
    @endif
@endsection
@section('title')
    {{ !empty($post) ? $post->title : trans('text.global.lbl_not_found') }}
@endsection
@section('css_files')
    <link rel="stylesheet" href="{{ asset('css/post/detail.css') }}?v={{ time() }}">
@endsection
