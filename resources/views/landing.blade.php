<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="@yield('description')">

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="@yield('description')">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Агрегатор объявлений об обмене валют">
        <meta property="og:image" content="/img/pig.svg">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="/css/app.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
        <link href="/css/landings.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
        <link rel="icon" type="image/x-icon" alt="icon" href="/img/valuta.ico">
        <link rel="apple-touch-icon" href="/img/pig.svg"/>

        @if( $table != 'donetsk' )
            @hasSection('canonical')
                <link rel="canonical" href="https://@yield('canonical')" />
            @endif
        @endif
    </head>
    <body class="antialiased landing">
        <div class="bg-gradient">
            <main class="main">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img width="70px" height="70px" alt="Обмен валют" class="logo-img" src="/img/pig.svg" >
                        <p class="logo-title">Обмен валют</p>
                    </a>
                    @include('parts.location')
                    
                    @if($locale['domain'] == 'valuta-dn') @include('parts.form') @endif
                    
                    @include('parts.searchform')
                </div>
                <a class="back-home landing-back-home" href="/">Вернуться</a>
                <div class="columns">
                    @section('main')
                    @show
                </div>
            </main>
        </div>
        <footer class="footer">
            @include('parts.footer')

            <script src="/js/app.js?v=@isset($hash){{$hash}}@endisset" defer></script>
        @production
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-4ZFLR96373"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-4ZFLR96373');
            </script>
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
            
                ym(90961172, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/90961172" style="position:absolute; left:-9999px;" alt="Включите JavaScript, пожалуйста" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
        @endproduction
    </body>
</html>
