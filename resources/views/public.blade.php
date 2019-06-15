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
            padding: 4px 10px 4px 10px;
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


        /* stylelint-disable at-rule-empty-line-before,at-rule-name-space-after,at-rule-no-unknown */
        /* stylelint-disable no-duplicate-selectors */
        /* stylelint-disable */
        /* stylelint-disable declaration-bang-space-before,no-duplicate-selectors,string-no-newline */
        .ant-cascader {
            font-family: "Chinese Quote", -apple-system, BlinkMacSystemFont, "Segoe UI", "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Helvetica Neue", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 14px;
            font-variant: tabular-nums;
            line-height: 1.5;
            color: rgba(0, 0, 0, 0.65);
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .ant-cascader-input.ant-input {
            background-color: transparent !important;
            cursor: pointer;
            width: 100%;
            position: relative;
            min-height: 40px;
        }
        .ant-cascader-picker-show-search .ant-cascader-input.ant-input {
            position: relative;
        }
        .ant-cascader-picker {
            font-family: "Chinese Quote", -apple-system, BlinkMacSystemFont, "Segoe UI", "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Helvetica Neue", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 14px;
            font-variant: tabular-nums;
            line-height: 1.5;
            color: rgba(0, 0, 0, 0.65);
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            list-style: none;
            position: relative;
            display: inline-block;
            cursor: pointer;
            background-color: #fff;
            border-radius: 0px;
            outline: 0;
            transition: color 0.3s;
            min-height: 40px;
            width: 100%;
            text-align: left;
        }
        .ant-cascader-picker-with-value .ant-cascader-picker-label {
            color: transparent;
        }
        .ant-cascader-picker-disabled {
            cursor: not-allowed;
            background: #f5f5f5;
            color: rgba(0, 0, 0, 0.25);
        }
        .ant-cascader-picker-disabled .ant-cascader-input {
            cursor: not-allowed;
        }
        .ant-cascader-picker:focus .ant-cascader-input {
            border-color: #40a9ff;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(24, 144, 255, 0.2);
            border-right-width: 1px !important;
        }
        .ant-cascader-picker-show-search.ant-cascader-picker-focused {
            color: rgba(0, 0, 0, 0.25);
        }
        .ant-cascader-picker-label {
            position: absolute;
            left: 0;
            height: 20px;
            line-height: 20px;
            top: 50%;
            margin-top: -10px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 100%;
            padding: 0 12px;
            color: #000;
        }
        .ant-cascader-picker-clear {
            opacity: 0;
            position: absolute;
            right: 12px;
            z-index: 2;
            background: #fff;
            top: 50%;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.25);
            width: 12px;
            height: 12px;
            margin-top: -6px;
            line-height: 12px;
            cursor: pointer;
            transition: color 0.3s ease, opacity 0.15s ease;
        }
        .ant-cascader-picker-clear:hover {
            color: rgba(0, 0, 0, 0.45);
        }
        .ant-cascader-picker:hover .ant-cascader-picker-clear {
            opacity: 1;
        }
        .ant-cascader-picker-arrow {
            position: absolute;
            z-index: 1;
            top: 50%;
            right: 12px;
            width: 12px;
            height: 12px;
            font-size: 12px;
            margin-top: -6px;
            line-height: 12px;
            color: rgba(0, 0, 0, 0.25);
            transition: transform 0.2s;
        }
        .ant-cascader-picker-arrow.ant-cascader-picker-arrow-expand {
            transform: rotate(180deg);
        }
        .ant-cascader-picker-small .ant-cascader-picker-clear,
        .ant-cascader-picker-small .ant-cascader-picker-arrow {
            right: 8px;
        }
        .ant-cascader-menus {
            font-size: 14px;
            background: #fff;
            position: absolute;
            z-index: 1050;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
        }
        .ant-cascader-menus ul,
        .ant-cascader-menus ol {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .ant-cascader-menus-empty,
        .ant-cascader-menus-hidden {
            display: none;
        }
        .ant-cascader-menus.slide-up-enter.slide-up-enter-active.ant-cascader-menus-placement-bottomLeft,
        .ant-cascader-menus.slide-up-appear.slide-up-appear-active.ant-cascader-menus-placement-bottomLeft {
            -webkit-animation-name: antSlideUpIn;
            animation-name: antSlideUpIn;
        }
        .ant-cascader-menus.slide-up-enter.slide-up-enter-active.ant-cascader-menus-placement-topLeft,
        .ant-cascader-menus.slide-up-appear.slide-up-appear-active.ant-cascader-menus-placement-topLeft {
            -webkit-animation-name: antSlideDownIn;
            animation-name: antSlideDownIn;
        }
        .ant-cascader-menus.slide-up-leave.slide-up-leave-active.ant-cascader-menus-placement-bottomLeft {
            -webkit-animation-name: antSlideUpOut;
            animation-name: antSlideUpOut;
        }
        .ant-cascader-menus.slide-up-leave.slide-up-leave-active.ant-cascader-menus-placement-topLeft {
            -webkit-animation-name: antSlideDownOut;
            animation-name: antSlideDownOut;
        }
        .ant-cascader-menu {
            display: inline-block;
            vertical-align: top;
            min-width: 111px;
            height: 180px;
            list-style: none;
            margin: 0;
            padding: 0;
            border-right: 1px solid #e8e8e8;
            overflow: auto;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .ant-cascader-menu:first-child {
            border-radius: 4px 0 0 4px;
        }
        .ant-cascader-menu:last-child {
            border-right-color: transparent;
            margin-right: -1px;
            border-radius: 0 4px 4px 0;
        }
        .ant-cascader-menu:only-child {
            border-radius: 4px;
        }
        .ant-cascader-menu-item {
            padding: 5px 12px;
            line-height: 22px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
        }
        .ant-cascader-menu-item:hover {
            background: #f00;
            color: #fff;
        }
        .ant-cascader-menu-item-disabled {
            cursor: not-allowed;
            color: rgba(0, 0, 0, 0.25);
        }
        .ant-cascader-menu-item-disabled:hover {
            background: transparent;
        }
        .ant-cascader-menu-item-active:not(.ant-cascader-menu-item-disabled),
        .ant-cascader-menu-item-active:not(.ant-cascader-menu-item-disabled):hover {
            background: #f5f5f5;
            font-weight: 600;
        }
        .ant-cascader-menu-item-expand {
            position: relative;
            padding-right: 24px;
        }
        .ant-cascader-menu-item-expand .ant-cascader-menu-item-expand-icon,
        .ant-cascader-menu-item-expand .ant-cascader-menu-item-loading-icon {
            display: inline-block;
            font-size: 12px;
            font-size: 10px \9;
            transform: scale(0.83333333) rotate(0deg);
            color: rgba(0, 0, 0, 0.45);
            position: absolute;
            right: 12px;
        }
        :root .ant-cascader-menu-item-expand .ant-cascader-menu-item-expand-icon,
        :root .ant-cascader-menu-item-expand .ant-cascader-menu-item-loading-icon {
            font-size: 12px;
        }
        .ant-cascader-menu-item .ant-cascader-menu-item-keyword {
            color: #f5222d;
        }

        .ant-cascader-input.ant-input {
            background-color: transparent !important;
            cursor: pointer;
            width: 100%;
            position: relative;
        }

        .ant-cascader-input.ant-input {
            background-color: transparent !important;
            cursor: pointer;
            width: 100%;
            position: relative;
        }

        .ant-input-lg {
            padding: 6px 11px;
            height: 40px;
            font-size: 16px;
        }

        .ant-input {
            font-family: "Chinese Quote", -apple-system, BlinkMacSystemFont, "Segoe UI", "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Helvetica Neue", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-variant: tabular-nums;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            list-style: none;
            position: relative;
            display: inline-block;
            padding: 4px 11px;
            width: 100%;
            height: 32px;
            font-size: 14px;
            line-height: 1.5;
            color: rgba(0, 0, 0, 0.65);
            background-color: #fff;
            background-image: none;
            border: 1px solid #d9d9d9;
            border-radius: 0px;
            transition: all 0.3s;
        }

        .ant-cascader-picker-label {
            position: absolute;
            left: 0;
            height: 20px;
            line-height: 20px;
            top: 50%;
            margin-top: -10px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 100%;
            padding: 0 12px;
        }

        .ant-cascader-picker-arrow {
            position: absolute;
            z-index: 1;
            top: 50%;
            right: 12px;
            width: 12px;
            height: 12px;
            font-size: 12px;
            margin-top: -6px;
            line-height: 12px;
            color: rgba(0, 0, 0, 0.25);
            transition: -webkit-transform 0.2s;
            transition: transform 0.2s;
            transition: transform 0.2s, -webkit-transform 0.2s;
        }

        .anticon {
            display: inline-block;
            font-style: normal;
            vertical-align: -0.125em;
            text-align: center;
            text-transform: none;
            line-height: 0;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin-right: 5px;
            font-size: 14px;
        }

        .anticon svg {
            display: inline-block;
        }

        .ant-cascader-menu-item-active:not(.ant-cascader-menu-item-disabled), .ant-cascader-menu-item-active:not(.ant-cascader-menu-item-disabled):hover {
            background: #ff6a6a;
            color: #fff;
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

</body>
</html>