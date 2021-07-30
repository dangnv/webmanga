<div class="row col-12 box-navigation">
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-md">
            @php
                $start = ($current_page < 4) ? 1 : ($current_page - 2);
                if ($start + 4 > $total_pages) {
                    $start = $total_pages - 3;
                }
                $previous = ($current_page <= 1) ? 1 : ($current_page - 1);
                $next = ($current_page >= $total_pages) ? $total_pages : ($current_page +1);
            @endphp
            <li class="page-item {{ $current_page <= 1 ? 'disabled' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').$route_name, ['page' => $previous]) }}"> < </a></li>
            @for ($page = $start; $page < ($start + 4); $page++)
                @if ($page <= $total_pages)
                    <li class="page-item {{ $page == $current_page ? 'active' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').$route_name, ['page' => $page]) }}">{{ $page }}</a></li>
                @endif
            @endfor
            <li class="page-item {{ $current_page >= $total_pages ? 'disabled' : '' }}"><a class="page-link" href="{{route(($is_night_mode ? 'night.' : '').$route_name, ['page' => $next]) }}"> > </a></li>
        </ul>
    </nav>
</div>
