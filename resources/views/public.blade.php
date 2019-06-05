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
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
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
        .price-not-discount {
            color: #333333;
        }
        .multiselect__option--highlight:after {
            background: #f00;
        }
        .multiselect__option--highlight {
            background: #f00;
        }
        .multiselect__tag {
            background: #cccccc;
            color: #000;
        }
        .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
            background: #f00;
        }
        .multiselect__tag-icon:after {
            color: #000;
        }
        .multiselect__tag, .multiselect__tags, .multiselect__tag-icon {
            border-radius: 0px;
        }

    </style>

    <script src="//unpkg.com/@textback/notification-widget@latest/build/index.js"></script>
</head>

<body>
<div id="app">
    <layout/>
</div>

<script type="text/javascript" encoding="utf-8">
    var _tbEmbedArgs = _tbEmbedArgs || [];
    (function () {
        var u =  "https://widget.textback.io/widget";
        _tbEmbedArgs.push(["widgetId", "2e91819d-622c-49b8-ae34-ca9575adab5d"]);
        _tbEmbedArgs.push(["baseUrl", u]);

        var d = document, g = d.createElement("script"), s = d.getElementsByTagName("script")[0];
        g.type = "text/javascript";
        g.charset = "utf-8";
        g.defer = true;
        g.async = true;
        g.src = u + "/widget.js";
        s.parentNode.insertBefore(g, s);
    })();

</script>

<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app-public.js') }}"></script>

<script src="/assets/public/js/vendor/jquery.min.js"></script>
<script src="/assets/public/js/bootstrap.bundle.js"></script>
<script src="/assets/public/js/bootstrap.js"></script>
<script type="text/javascript" src="/assets/public/js/jquery.touchSwipe.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="https://unpkg.com/ionicons@4.4.6/dist/ionicons.js"></script>
<script src="/assets/public/js/autocomplete.js"></script>

</body>
</html>