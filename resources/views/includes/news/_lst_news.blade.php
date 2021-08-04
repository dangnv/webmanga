@foreach($news as $item)
    <a href="{{ route(($is_night_mode ? 'night.' : '').'news.detail', ['slug' => $item->slug]) }}" class="row box-news">
        <div class="image">
            <img src="{{ $item->thumbnail }}">
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 comtent-news">
            <div class="media-content">
                <div class="content">
                    <div class="mtitle">
                        {!! $item->title !!}
                    </div>
                    <p class="msub">{{ $item->public_at ? \Carbon\Carbon::create($item->public_at)->format('M d Y') : '' }}</p>
                </div>
                <div class="ellipsis is-ellipsis-1 is-fixed-bottom">
                    <p>{!! $item->description !!}</p>
                </div>
            </div>
        </div>
    </a>
@endforeach
