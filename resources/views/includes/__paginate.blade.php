@if ($total_pages > 1)
<div class="row col-12 box-navigation">
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-md">
            @php
                $start = ($current_page < 4 || $total_pages <= 4) ? 1 : ($current_page - 2);
                if ($start <= 0) {
                    $start = 1;
                } else if ($total_pages > 4 && $start + 4 > $total_pages) {
                    $start = $total_pages - 3;
                }
                $previous = ($current_page <= 1) ? 1 : ($current_page - 1);
                $next = ($current_page >= $total_pages) ? $total_pages : ($current_page +1);
                $params = [];
                if (isset($category) && !empty($category)) {
                    $params['slug'] = $category->slug;
                }
                if (isset($key_word) && !empty($key_word)) {
                    $params['q'] = $key_word;
                }
            @endphp
            <li class="page-item {{ $start <= 0 }} {{ $current_page <= 1 ? 'disabled' : '' }}">
                @php $params['page'] = $previous; @endphp
                <a class="page-link" href="{{ route(($is_night_mode ? 'night.' : '').$route_name, $params) }}"> < </a>
            </li>
            @for ($page = $start; $page < ($start + 4); $page++)
                @if ($page <= $total_pages)
                    @php $params['page'] = $page; @endphp
                    <li class="page-item {{ $page == $current_page ? 'active' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').$route_name, $params) }}">{{ $page }}</a></li>
                @endif
            @endfor
            @php $params['page'] = $next; @endphp
            <li class="page-item {{ $current_page >= $total_pages ? 'disabled' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').$route_name, $params) }}"> > </a></li>
        </ul>
    </nav>
</div>
@endif
