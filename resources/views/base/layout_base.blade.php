<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/logos/Inuyasha_avatar.png') }}" type="image/gif" sizes="16x16">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="" type="image/x-icon" />

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
    <meta property="og:image" content="{{ asset('images/logos/Inuyasha_avatar.png') }}">
    <meta name="twitter:image" content="{{ asset('images/logos/Inuyasha_avatar.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <meta name="clckd" content="129aa841c7db5442b505beb375ec6660" />

    <title>Manga Web - @yield('title')</title>

    <link rel="icon" href="{{ asset('images/logos/Inuyasha_avatar.png') }}" type="image/gif" sizes="16x16">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}?v={{ env('STATIC_VER', '20210726') }}">
    @yield('css_files')

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/base.js') }}"></script>
    @yield('js_files')
</head>
<body @if ($is_night_mode) class="night-mode" @endif>
<div class="row">
    @include('includes.base.menu')
</div>
<div class="row">
    {{--Col for content--}}
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
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
                    <li class="list-inline-item"><a class="social-icon text-center" target="_blank" href="#">About Us</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" target="_blank" href="#">Term of Use</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" target="_blank" href="#">Privacy Policy</a> |</li>
                    <li class="list-inline-item"><a class="social-icon text-center" target="_blank" href="#">Report error manga</a></li>
                </ul>
            </div>
            <h5 class="card-title">Why You Should Read Manga Online at MangaYeh.com ?</h5>
            <p class="card-text">
                There are many reasons you should read Manga online, and if you are a fan of this unique storytelling style then learning about them is a must. One of the biggest reasons why you should read Manga online is the money it can save you. While there's nothing like actually holding a book in your hands, there's also no denying that the cost of those books can add up quickly. So why not join the digital age and read Manga online? Another big reason to read Manga online is the huge amount of material that is available. When you go to a comic store or other book store their shelves are limited by the space that they have. When you go to an online site to read Manga those limitations don't exist. So if you want the best selection and you also want to save money then reading Manga online should be an obvious choice for you
            </p>
        </div>
    </div>
</div>
</body>
</html>
