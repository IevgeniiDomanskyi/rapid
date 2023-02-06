<!doctype html>
<html translate="no">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1.0, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="google" content="notranslate" />
        <title>Rapid Training</title>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.png" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet" />
        <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7BZ1G7CN71"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-7BZ1G7CN71');
        </script>

        <script>
            let serverMessage = []
        </script>

        @if ( ! empty($data))
        <script>
            serverMessage = <?php echo json_encode($data); ?>;
        </script>
        @endif
    </head>

    <body>
        <div id="app"></div>

        <script src="/js/rxp-js.min.js" type="text/javascript"></script>
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>

        <script data-cfasync="false">
            (function(W,i,s,e,P,o,p){W['WisePopsObject']=P;W[P]=W[P]||function(){(W[P].q=W[P].q||[]).push(arguments)},W[P].l=1*new Date();o=i.createElement(s),p=i.getElementsByTagName(s)[0];o.defer=1;o.src=e;p.parentNode.insertBefore(o,p)})(window,document,'script','//loader.wisepops.com/get-loader.js?v=1&site=dCyWTDPrCc','wisepops');
        </script>
    </body>
</html>