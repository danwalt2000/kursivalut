<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="Курс {{ $title->h1->currency }}{{ $title->h1->locale }}{{ $title->description->hours }} на черном рынке.">
        <meta name="keywords" content="купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка" />

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="Курс {{ $title->h1->currency }}{{ $title->h1->locale }}{{ $title->description->hours }} на черном рынке.">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Аггрегатор объявлений об обмене валют">
        <meta property="og:image" content="/img/pig.svg">
        
        <meta name="yandex-verification" content="831c8687bb83c11a" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="/css/app.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
        @if($table == 'donetsk')
            <link rel="icon" type="image/svg+xml" alt="icon" href="/img/favicon.svg">
            <link rel="apple-touch-icon" type="image/svg+xml" href="/img/favicon.svg"/>
        @else
            <link rel="icon" type="image/x-icon" alt="icon" href="/img/valuta.ico">
            <link rel="apple-touch-icon" type="image/svg+xml" href="/img/favicon.svg"/>
        @endif

        {{-- На страницах фильтрации по дате и курсу дублируется контент, поэтому нужен canonical --}}
        @if( Request::get('date') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @elseif( Request::get('rate') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @endif
        <!-- Yandex.RTB -->
        <script>window.yaContextCb=window.yaContextCb||[]</script>
        <script src="https://yandex.ru/ads/system/context.js" async></script>
    </head>
    <body class="antialiased {{$add_class}}">
        <div class="bg-gradient">
            <main class="main">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img width="70px" height="70px" alt="Обмен валют" class="logo-img" src="/img/favicon.svg" >
                        <p class="logo-title">Обмен валют</p>
                    </a>
                    @include('parts.location')
                    
                    @if($locale['domain'] == 'valuta-dn') @include('parts.form') @endif
                    
                    @include('parts.searchform')
                </div>
                <div class="columns">
                    @section('main')
                    @show

                    <div class="right_column">
                        @include('parts.sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])    

                        <div class="additional-info">
                            <a class="additional-info-a" href="/legal">Правовая информация</a>
                        </div>
                        @isset($locale['yandex-ad'])
                            @desktop
                                <div class="desktop-yandex-ads">
                                    @include('parts.yandex-ad')    
                                </div>
                            @enddesktop
                        @endisset
                    </div>
                </div>
            </main>
        </div>
        <footer class="footer">
            <div class="footer_content">
                Технический партнер <a href="https://sharpdesign.ru">SharpDesign</a>.
            </div>
            <div id="scroll-to-top" class="scroll-to-top scroll-to-top_inactive"></div>
        </footer>
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
    
                
                const scrollToTop = document.querySelector("#scroll-to-top");
                scrollToTop.addEventListener("click", function(){
                    window.scrollTo({top: 0, left: 0, behavior: "smooth", });
                });
                // Detect when scrolled to bottom.
                if( window.ifMore > 1){
                    document.addEventListener('scroll', function() {
                        if ( window.pageYOffset + window.screen.height >= feed.scrollHeight) {
                            loadMore();
                        }
                        if(window.pageYOffset > window.screen.height){
                            scrollToTop.classList.add("scroll-to-top_active");
                            scrollToTop.classList.remove("scroll-to-top_inactive");
                        } else{
                            scrollToTop.classList.remove("scroll-to-top_active");
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
            
                ym({{$metrika}}, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/{{$metrika}}" style="position:absolute; left:-9999px;" alt="Включите JavaScript, пожалуйста" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
        @endproduction
    </body>
</html>
