@foreach($posts as $post)
    <div href="#" class="row col-xs-12 col-sm-12 col-md-12 col-lg-6 box-on-going">
        <div class="image">
            <img src="{{ $post->thumbnail }}">
        </div>
        <div class="col-lg-9 col-8">
            <div class="media-content">
                <div class="content">
                    <div class="mtitle">
                        @if ($post->is_new == \App\Models\Post::STATUS_NEW)<span class="badge badge-pill badge-success">New</span>@endif
                        {{ $post->title }}
                    </div>
                    <p class="msub">
                        {{ ($post->status == \App\Models\Post::STATUS_ON_GOING) ? trans('text.home.lbl_status_on_going') : trans('text.home.lbl_status_completed') }}
                        · {{ $post->published_date ? \Carbon\Carbon::create($post->published_date)->format('M d Y') : '' }}</p>
                    <span class="ellipsis is-ellipsis-1">
                            @php $lastChapter = \App\Models\Post::getLastChapter($post->id) @endphp
                            Last chapter: <span style="font-weight: 500;">{{ count($lastChapter) > 0  ? $lastChapter[0]->title : '' }}</span>
                        </span>
                </div>
                <div class="ellipsis is-ellipsis-1 is-fixed-bottom">
                    @if ($post->categories)
                        @foreach($post->categories as $cate)
                            <span class="tag is-light is-small">{{ $cate->category ? $cate->category->name : '' }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
