<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="Черный рынок. {{ $h1 }} - объявления реальных людей">
        <meta name="keywords" content="купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка" />

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="Черный рынок. {{ $h1 }} - объявления реальных людей">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Аггрегатор объявлений об обмене валют">
        <meta property="og:image" content="/img/pig.svg">
        <meta name="yandex-verification" content="831c8687bb83c11a" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="/css/app.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
        <link rel="icon" type="image/svg+xml " alt="icon" href="/img/favicon.svg">
        <link rel="apple-touch-icon" href="/img/favicon.svg"/>

        {{-- На страницах фильтрации по дате дублируется контент, поэтому нужен canonical --}}
        @if( Request::get('date') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @elseif( Request::get('rate') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @endif
    </head>
    <body class="antialiased {{$add_class}}">
        <div class="bg-gradient">
            <main class="main">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img width="70px" height="70px" alt="Обмен валют" class="logo-img" src="/img/pig.svg" >
                        <p class="logo-title">Обмен валют</p>
                    </a>
                    <div class="location modal-wrapper">
                        <span class="modal-open location-modal-open">{{$locale['title']}}</span>
                        <div class="popup-bg modal-hidden">
                            <div class="modal-inner location-popup">
                                <span class="modal-close close_modal"></span>
                                <p class="location-title">Выберите город</p>
                                <ul class="location-list">
                                    @foreach($locales as $city)
                                        @if($city['name'] == $locale['name'])
                                            <li class="location-city location_current-city">
                                                <span>{{$city['title']}}</span>
                                        @else
                                            <li class="location-city">
                                                <a href="//@if($city['name'] != 'donetsk'){{$city['name']}}.@endif{{$city['domain']}}.{{env('ENV_DOMAIN')}}">
                                                    {{$city['title']}}
                                                </a>
                                        @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @include('form')
                    <span id="open-search" class="search open-search"></span>
                    <form action="{{ url("s") }}" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях" name="search" value=""
                            class="search-input @error('search') is-invalid @enderror" minlength="1" maxlength="30" required>
                        <label class="search-label" for="search">Поиск в объявлениях</label>
                        <button id="search-submit" type="submit" class="search-submit" title="Поиск">
                            <img alt="Поиск" class="search" src="/img/search.svg" alt="" width="18" height="18">
                        </button>  
                    </form>
                </div>
                <div class="columns">
                    @section('main')
                    @show

                    <div class="right_column">
                        @include('sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])    

                        <div class="additional-info">
                            <a class="additional-info-a" href="/legal">Правовая информация</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <footer class="footer">
            <div class="footer_content">
                Технический партнер <a href="https://sharpdesign.ru">SharpDesign</a>.</footer>
            </div>
        <script>
            window.ifMore = Math.ceil( Number("{{ $ads_count / 20 }}"));
            window.feedStatus = 1;
            window.currentHeight = 0;
            
            let currency = "{{ $path['currency'] }}";
            const constructUrl = function (){
                let url = "/ajax?" 
                url += "sellbuy=" + "{{ $path['sell_buy'] }}&amp;";
                if(currency) url += "currency=" + currency + "&amp;";
                url += "offset=" + window.feedStatus + "&amp;";
                url += "{{ $path['query'] }}";
                url = url.replaceAll('&amp;', '&');
                return url;
            }

            window.addEventListener('DOMContentLoaded', () => {
                var feed = document.querySelector('#feed');
                let url = constructUrl();
                   
                const loadMore = function() {  
                    // если дошли до конца записей
                    if(window.feedStatus >= window.ifMore) return;
    
                    // условие, чтобы функция не срабатывала несколько раз при скроллинге
                    if( window.currentHeight && window.currentHeight + 1000 > window.pageYOffset ) return;
                    window.currentHeight = window.pageYOffset;
                    
                    function reqListener () {
                        window.feedStatus++;
                        url = constructUrl();
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
            });
        </script>
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
