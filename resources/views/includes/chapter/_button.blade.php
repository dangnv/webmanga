<div class="row box-list-chapters">
    <div>
        <button class="form-control btn btn-outline-primary" onclick="previous()">Previous</button>
    </div>
    <div>
        <select class="form-control" id="change-chapter">
            @foreach($post->chapters as $key => $item)
                <option value="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $item->slug]) }}"
                        data-url="{{ route(($is_night_mode ? 'night.' : '').'chapters.detail', ['post_slug' => $post->slug, 'chapter_slug' => $post->chapters[0]->slug]) }}"
                        data-key="{{ $key }}"
                        id="option-chapter-{{ $key }}"
                        {{ ($item->id == $chapter->id) ? 'selected' : '' }}>
                    {{ $item->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <button class="form-control btn btn-outline-primary" onclick="next()">Next</button>
    </div>
</div>
