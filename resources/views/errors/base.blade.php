<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="Ошибка: @yield('title')">
        <meta name="keywords" content="купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка" />

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="/css/app.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" alt="icon" href="/img/valuta.ico">
        <link rel="canonical" href="{{Request::url()}}" />
    </head>
    <body class="antialiased">
        <div class="bg-gradient">
            <main class="main">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img width="70px" height="70px" alt="Обмен валют" class="logo-img" src="/img/pig.svg" >
                        <p class="logo-title">Обмен валют</p>
                    </a>
                    <img alt="Открыть строку поиска" id="open-search" class="search open-search" src="/img/search-white.svg" alt="" width="18" height="18">
                    <form action="{{ url("s") }}" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях" name="search" value=""
                            class="search-input @error('search') is-invalid @enderror" minlength="1" maxlength="30" required>
                        <button id="search-submit" type="submit" class="search-submit" title="Поиск">
                            <img alt="Поиск" class="search" src="/img/search.svg" alt="" width="18" height="18">
                        </button>  
                    </form>
                </div>
                <div class="columns">
                    @section('main')
                    @show

                    {{-- <div class="right_column">
                        @include('form')
                    </div> --}}
                </div>
            </main>
        </div>
        <footer class="footer">Сайт разработан <a href="https://sharpdesign.ru">SharpDesign</a>.</footer>
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
