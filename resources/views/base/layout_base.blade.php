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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    @yield('css_files')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js_files')
</head>
<body>
<div class="row">
    @include('includes.base.menu')
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <div class="row header-search header-fixed">
        </div>
        <div class="row content-part">
            @yield('content-page')
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sidebar-part">
        <div class="row sidebar-keywords">

        </div>
        <div class=row"sidebar-categories">

        </div>
        <div class="row sidebar-popular-posts">

        </div>
    </div>
</div>
</body>
</html>
