@if (!$is_night_mode && isset($is_home))
    <div class="row columns is-mobile text-center">
        <div class="col-12 column box-night-mode">
            <a class="turn-night-mode box has-text-centered" href="{{ route('night.home') }}">
                <img src="https://mangayeh.com/img/night-mode.svg" style="width: 32px; height: 32px;">
                <h2 class="title is-4">{{ trans('text.home.btn_night_mode') }}</h2>
            </a>
        </div>
    </div>
@endif
<div class="row box-search">
    <div class="col-12 col-auto">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Find a manga..." aria-label="Search"
                   aria-describedby="search-btn">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="search-btn">
                    <img src="{{ asset('images/logos/search_white_24dp.svg') }}">
                    {{ trans('text.global.btn_search') }}
                </button>
            </div>
        </div>
    </div>
</div>
