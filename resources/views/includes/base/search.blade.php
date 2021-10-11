@if (isset($is_show_search) && $is_show_search)
<div class="row box-search">
    <div class="col-12 col-auto">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Find a manga..." aria-label="Search"
                   value="{{ isset($key_word) ? $key_word : ''}}"
                   id="ipt-search-manga"
                   data-url="{{ route(($is_night_mode ? 'night.' : '').'post.search', ['q' => '']) }}"
                   onchange="window.location.href=$(this).data('url')+$(this).val()"
                   aria-describedby="search-btn">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="search-btn" onclick="window.location.href=$('#ipt-search-manga').data('url')+$('#ipt-search-manga').val()">
                    <img src="{{ asset('image/search_white_24dp.svg') }}">
                    {{ trans('text.global.btn_search') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endif
