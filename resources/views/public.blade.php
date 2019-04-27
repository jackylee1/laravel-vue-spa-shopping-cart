<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/public/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/public/css/normalize.min.css">
    {!! style_ts('/assets/public/css/style.css') !!}
    {!! style_ts('/assets/public/css/account.css') !!}
    <link rel="stylesheet" href="/assets/public/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/public/css/jquery.fancybox.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
          crossorigin="anonymous">
    <link href="https://unpkg.com/ionicons@4.4.6/dist/css/ionicons.min.css" rel="stylesheet">

    <style type="text/css">
        .fb_iframe_widget span, iframe.fb_iframe_widget_lift,
        .fb_iframe_widget iframe {
            width: 100% !important;
            min-height: 300px !important;
        }
    </style>
</head>

<body>
<div id="app">
    <layout/>
</div>

<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/public.js') }}"></script>

<script src="/assets/public/js/vendor/jquery.min.js"></script>
<script src="/assets/public/js/bootstrap.bundle.js"></script>
<script src="/assets/public/js/bootstrap.js"></script>
<script type="text/javascript" src="/assets/public/js/jquery.touchSwipe.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="https://unpkg.com/ionicons@4.4.6/dist/ionicons.js"></script>

</body>
</html>