<nav class="navbar has-shadow is-small">
    <div class="container">
        <div class="navbar-brand">
            <a href="/" class="navbar-item" style="color: inherit !important; margin-right: auto;">
                <img src="{{ asset('images/logos/Inuyasha_avatar.png') }}" width="25px" height="25px">
                <span style="font-size: 20px; font-weight: 700;" class="ml-1">
                    {{ env('APP_TITLE_PAGE') }}
                </span>
            </a>
            <div class="navbar-end is-hidden-desktop">
                <div class="navbar-item">
                    <a class="navbar-item bd-navbar-item bd-navbar-item-base" href="http://mangarok.mobi">
                        <img src="https://mangayeh.com/img/night-mode.svg">
                    </a>
                    <a href="/signin" class="navbar-item bd-navbar-item bd-navbar-item-base">
                        <span>Login</span>
                    </a>
                    <a href="/signup" class="navbar-item bd-navbar-item bd-navbar-item-base">
                        <span>Register</span>
                    </a>
                </div>
            </div>
            <div class="navbar-burger" data-target="mangayeh-menu" style="margin-left: 0;" onclick="this.classList.toggle('is-active');document.getElementById('mangayeh-menu').classList.toggle('is-active');">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbar-menu navbar-start" id="mangayeh-menu">
            <div class="navbar-item">
                <a href="/category/latest" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>Latest</span>
                </a>
                <a href="/category/completed" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>Completed</span>
                </a>
                <a href="/category/newest" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>Newest</span>
                </a>
                <a href="/category/all" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>All</span>
                </a>
                <a href="/news" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>Article</span>
                </a>
                <a href="https://covicomic.com" class="navbar-item bd-navbar-item bd-navbar-item-base">
                    <span>Comic</span>
                </a>
            </div>
        </div>
        <div class="navbar-end is-hidden-mobile is-hidden-tablet-only">
            <div class="navbar-end">
                <a class="navbar-item bd-navbar-item bd-navbar-item-base" href="/signin">
                    <span>Login</span>
                </a>
                <a class="navbar-item bd-navbar-item bd-navbar-item-base" href="/signup">
                    <span>Register</span>
                </a>
            </div>
        </div>
    </div>
</nav>
