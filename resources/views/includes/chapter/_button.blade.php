<div class="row box-list-chapters">
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <button class="form-control btn btn-outline-primary">Previous</button>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <select class="form-control">
            @foreach($post->chapters as $item)
                <option value="{{ $item->slug }}" data-url="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $post->chapters[0]->slug]) }}">
                    {{ $item->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <button class="form-control btn btn-outline-primary">Next</button>
    </div>
</div>
