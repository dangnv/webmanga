<div class="col-12">
    <nav class="navbar navbar-expand-sm {{ $is_night_mode ? 'navbar-dark bg-dark' : 'navbar-light bg-light' }}">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logos/Inuyasha_avatar.png') }}" width="16px" height="16px">
            {{ env('APP_TITLE_PAGE') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            @if (!$is_night_mode)
                <a class="turn-night-mode is-mobile-night-mode-btn" href="{{ route('night.home') }}">
                    <img width="20px" height="20px" src="https://mangayeh.com/img/night-mode.svg">
                </a>
            @endif
            <a class="is-mobile-login-btn" href="#">{{ trans('text.menu.btn_login') }}</a>
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                @if ($is_night_mode)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ trans('text.menu.lbl_home') }}</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.lbl_latest') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.lbl_completed') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.lbl_newest') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.lbl_all') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.lbl_news') }}</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse box-login">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    @if ($is_night_mode)
                        <a class="turn-night-mode nav-link" href="{{ route('night.home') }}">
                            <img width="20px" height="20px" src="https://mangayeh.com/img/night-mode.svg">
                        </a>
                    @endif
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ trans('text.menu.btn_login') }}</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
