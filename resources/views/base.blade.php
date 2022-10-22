<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="Агрегатор объявлений о купле-продаже валюты в Донецке и области">
        <meta name="keywords" content="купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка" />

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="Агрегатор объявлений о покупке и продаже валюты">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="bg-gradient">

            <main class="main">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img class="logo-img" src="/img/pig.svg" >
                        <p class="logo-title">Обмен валют</p>
                    </a>
                    <form action="{{ url("s") }}" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях" name="search" value=""
                            class="search-input @error('search') is-invalid @enderror" minlength="1" maxlength="30" required>
                        <button type="submit" class="search-submit">
                            <img class="search" src="/img/search.svg" alt="" width="18" height="18">
                        </button>  
                    </form>
                </div>
                <div class="columns">
                    @section('main')
                    @show

                    <div class="right_column">
                        @include('form')
                    </div>
                </div>
            </main>
        </div>
        <script>
            window.ifMore = Math.ceil( Number("{{ $ads_count / 20 }}"));
            window.feedStatus = 0;
            window.currentHeight = 0;

            var feed = document.querySelector('#feed');
            let currency = "{{ $path['currency'] }}";
            let url = "/ajax?" 
            url += "sellbuy=" + "{{ $path['sell_buy'] }}&amp;";
            if(currency) url += "currency=" + currency + "&amp;";
            url += "offset=" + window.feedStatus + "&amp;";
            url += "{{ $path['query'] }}";
            url = url.replaceAll('&amp;', '&');

            var loadMore = function() {  
                // если дошли до конца записей
                if(window.feedStatus >= window.ifMore - 1) return;

                // условие, чтобы функция не срабатывала несколько раз при скроллинге
                if( window.currentHeight && window.currentHeight + 1000 > window.pageYOffset ) return;
                window.currentHeight = window.pageYOffset;
                
                function reqListener () {
                    window.feedStatus++;
                    var item = document.createElement('div');
                    item.innerHTML = this.responseText;
                    feed.appendChild(item);
                }
                const req = new XMLHttpRequest();
                req.addEventListener("load", reqListener);
                req.open("GET", url);
                req.send();
            }

            // Detect when scrolled to bottom.
            if( window.ifMore > 1){
                document.addEventListener('scroll', function() {
                    if ( window.pageYOffset + window.screen.height >= feed.scrollHeight) {
                        loadMore();
                    }
                });
            }
        </script>
        <script src="/js/app.js" defer></script>
    </body>
</html>
