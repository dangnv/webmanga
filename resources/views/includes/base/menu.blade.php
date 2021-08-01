<div class="col-12" id="menu">
    <nav class="navbar navbar-expand-sm {{ $is_night_mode ? 'navbar-dark bg-dark' : 'navbar-light bg-light' }}">
        <a class="navbar-brand" href="{{ route(($is_night_mode ? 'night.' : '').'home') }}">
            <img src="{{ asset('images/logos/Inuyasha_avatar.png') }}" width="16px" height="16px">
            {{ env('APP_TITLE_PAGE') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        @if (\Illuminate\Support\Facades\Auth::check())
            <a class="navbar-toggler is-mobile-login-btn box-profile" href="{{ route(($is_night_mode ? 'night.' : '').'profile.index') }}">
                <img src="{{ \Illuminate\Support\Facades\Auth::user()->avatar }}" />
                <span class="lbl-user-name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
            </a>
        @else
            <a class="navbar-toggler is-mobile-login-btn" href="#login" onclick="$('#modal_login').modal('show')">{{ trans('text.menu.btn_login') }}</a>
        @endif
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                @if ($is_night_mode)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ trans('text.menu.lbl_home') }}</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(($is_night_mode ? 'night.' : '').'post.latest') }}">{{ trans('text.menu.lbl_latest') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(($is_night_mode ? 'night.' : '').'post.completed') }}">{{ trans('text.menu.lbl_completed') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(($is_night_mode ? 'night.' : '').'post.newest') }}">{{ trans('text.menu.lbl_newest') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(($is_night_mode ? 'night.' : '').'post.all') }}">{{ trans('text.menu.lbl_all') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(($is_night_mode ? 'night.' : '').'news.index') }}">{{ trans('text.menu.lbl_news') }}</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse box-login">
            <ul class="navbar-nav mr-auto">
<!--                <li class="nav-item">
                    @if ($is_night_mode)
                        <a class="turn-night-mode nav-link" href="{{ route('night.home') }}">
                            <img width="20px" height="20px" src="https://mangayeh.com/img/night-mode.svg">
                        </a>
                    @endif
                </li>-->
                @if (\Illuminate\Support\Facades\Auth::check())
                <li class="nav-item">
                    <a class="nav-link box-profile" href="{{ route(($is_night_mode ? 'night.' : '').'profile.index') }}">
                        <img src="{{ \Illuminate\Support\Facades\Auth::user()->avatar }}" />
                        <span class="lbl-user-name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                    </a>
                </li>
                <li class="nav-item nav-logout">
                    <a class="nav-link" href="#logout" onclick="$('#form-logout').submit()">{{ trans('text.menu.btn_logout') }}</a>
                    <form action="{{ route('logout') }}" method="post" id="form-logout">
                        @csrf
                    </form>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="#login" onclick="$('#modal_login').modal('show')">{{ trans('text.menu.btn_login') }}</a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
</div>
