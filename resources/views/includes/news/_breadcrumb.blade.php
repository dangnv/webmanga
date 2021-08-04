<div class="row col-12">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb article-lst">
            <li class="breadcrumb-item"><a href="{{ route(($is_night_mode ? 'night.' : '').'home') }}">{{ env('APP_TITLE_PAGE') }}</a></li>
            @if(isset($isNewsDetail) && $isNewsDetail)
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route(($is_night_mode ? 'night.' : '').'news.index') }}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">{!! $article->title !!}</li>
            @else
                <li class="breadcrumb-item active" aria-current="page">News</li>
            @endif
        </ol>
    </nav>
</div>
