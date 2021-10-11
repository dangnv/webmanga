<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">

    <meta name="description" content="✔️Reading manga free with latest chapter, free with high-quality images, read manga on mobile, read comic online (free)">
    <meta name="keywords" content="hot manga, naruto, top manga, latest manga, batman, mangayeh, dear door">
    <meta name="robots" content="All,index,follow">

    <meta property="og:type" content="website">
    <meta property="og:image:type" content="image/png">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="✔️Read manga online (100% free) - Fastest">
    <meta property="og:description" content="✔️Reading manga free with latest chapter, free with high-quality images, read manga on mobile, read comic online (free)">
    <meta property="og:site_name" content="{{ env('APP_URL') }}">

    <meta name="twitter:title" content="✔️Read manga online (100% free) - Fastest">
    <meta name="twitter:description" content="✔️Reading manga free with latest chapter, free with high-quality images, read manga on mobile, read comic online (free)">
    <meta property="og:image" content="{{ asset('image/icon/logo1024.png') }}">
    <meta name="twitter:image" content="{{ asset('image/icon/logo1024.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="clckd" content="129aa841c7db5442b505beb375ec6660" />

    <link rel="apple-touch-icon" sizes="57x57" href="image/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="image/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="image/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="image/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="image/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="image/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="image/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="image/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="image/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="image/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="image/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="image/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/icon/favicon-16x16.png">
    <link rel="shortcut icon" href="image/icon/favicon-96x96.png" type="image/x-icon" />
    <link rel="manifest" href="image/icon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="image/icon/ms-icon-144x144.png">
    <meta name="msapplication-starturl" content="/">
    <meta name="theme-color" content="#ffffff">

    <title>{{env('APP_TITLE_PAGE')}} - @yield('title')</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/home/night_mode.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    @yield('css_files')

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/base.js') }}?v={{ time() }}"></script>
    @yield('js_files')
</head>
<body>
<div class="row">
    @include('includes.base.menu')
</div>
<div class="row">
    {{--Col for content--}}
    <div class="col-xs-12 col-sm-12 col-md-12 {{ !isset($tags) && !isset($categories) && !isset($popular_posts) ? 'col-12' : 'col-lg-8' }}">
        @include('includes.base.search')
        <div class="row content-part">
            @yield('content-page')
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
        @include('includes.base.sidebar')
    </div>
</div>
<div class="row footer">
    <div class="card col-12">
        <div class="card-body">
            <div class="col-12 container">
                <ul class="list-inline">
                    <li class="list-inline-item"><a class="social-icon text-center" href="{{ route(($is_night_mode ? 'night.' : '').'about') }}">About Us</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" href="{{ route(($is_night_mode ? 'night.' : '').'term') }}">Term of Use</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" href="{{ route(($is_night_mode ? 'night.' : '').'privacy') }}">Privacy Policy</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" href="{{ env('APP_REPORT_LINk', '#') }}" target="_blank">Report error manga</a></li>
                </ul>
            </div>
            <h5 class="card-title">Why You Should Read Manga Online at {{ env('APP_TITLE_PAGE') }} ?</h5>
            <p class="card-text">
                There are many reasons you should read Manga online, and if you are a fan of this unique storytelling style then learning about them is a must. One of the biggest reasons why you should read Manga online is the money it can save you. While there's nothing like actually holding a book in your hands, there's also no denying that the cost of those books can add up quickly. So why not join the digital age and read Manga online? Another big reason to read Manga online is the huge amount of material that is available. When you go to a comic store or other book store their shelves are limited by the space that they have. When you go to an online site to read Manga those limitations don't exist. So if you want the best selection and you also want to save money then reading Manga online should be an obvious choice for you
            </p>
        </div>
    </div>
</div>
@include('includes.base.__modal_login')
</body>
</html>
