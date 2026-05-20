@php
    $defaultDescription = isset($title)
        ? "Курс {$title->h1->currency}{$title->h1->locale}{$title->description->hours} на черном рынке."
        : '';
    $metaDescription = trim($__env->yieldContent('meta_description', $defaultDescription));
    $ogDescription = trim($__env->yieldContent('og_description', $metaDescription));
    $metaKeywords = trim($__env->yieldContent('meta_keywords', 'купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка'));
    $bodyClass = trim($__env->yieldContent('body_class', $add_class ?? ''));
    $faviconHref = trim($__env->yieldContent('favicon_href', '/img/favicon.svg'));
    $faviconType = trim($__env->yieldContent('favicon_type', 'image/svg+xml'));
    $appleTouchIcon = trim($__env->yieldContent('apple_touch_icon', $faviconHref));
    $appleTouchType = trim($__env->yieldContent('apple_touch_type', $faviconType));
    $logoLinkHref = trim($__env->yieldContent('logo_link_href', '/'));
    $headerLogoImage = trim($__env->yieldContent('header_logo_image', $faviconHref));
    $headerLogoAlt = trim($__env->yieldContent('header_logo_alt', 'Обмен валют'));
    $headerLogoTitle = trim($__env->yieldContent('header_logo_title', 'Обмен валют'));
    $canonicalUrl = trim($__env->yieldContent('canonical_url'));
    $showRightColumn = trim($__env->yieldContent('show_right_column', '1')) !== '0';
    $showScrollToTop = trim($__env->yieldContent('show_scroll_to_top', '1')) !== '0';
    $showGdpr = trim($__env->yieldContent('show_gdpr', '1')) !== '0';
    $showModal = trim($__env->yieldContent('show_modal', '1')) !== '0';
    $showPageScripts = trim($__env->yieldContent('show_page_scripts', '1')) !== '0';
    $showAppJs = trim($__env->yieldContent('show_app_js', '1')) !== '0';
    $showYandexAdScript = trim($__env->yieldContent('show_yandex_ad_script', '1')) !== '0';
    $metrikaId = trim($__env->yieldContent('yandex_metrika', $metrika ?? '90961172'));
@endphp
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="keywords" content="{{ $metaKeywords }}">

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="{{ $ogDescription }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Агрегатор объявлений об обмене валют">
        <meta property="og:image" content="/img/pig.svg">
        
        <meta name="yandex-verification" content="831c8687bb83c11a" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @include('parts.theme-head')

        <link href="/css/app.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
        @yield('extra_stylesheets')
        <link id="favicon" rel="icon" type="{{ $faviconType }}" alt="icon" href="{{ $faviconHref }}">
        <link rel="apple-touch-icon" type="{{ $appleTouchType }}" href="{{ $appleTouchIcon }}"/>
        @yield('extra_head')

        @if($canonicalUrl !== '')
            <link rel="canonical" href="{{ $canonicalUrl }}" />
        @elseif( Request::get('date') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @elseif( Request::get('rate') )
            <link rel="canonical" href="{{ Request::url() }}" />
        @endif
        @production
            @if($showYandexAdScript && isset($locale['yandex-ad']))
                    <script>window.yaContextCb=window.yaContextCb||[]</script>
                    <script src="https://yandex.ru/ads/system/context.js" async></script>
            @endif
        @endproduction
    </head>
    <body class="antialiased {{ $bodyClass }}">
        <div class="bg-gradient">
            <main class="main">
                <header class="logo-wrapper">
                    <div class="logo">
                        <a href="{{ $logoLinkHref }}" class="logo-link">
                            <img width="70px" height="70px" alt="{{ $headerLogoAlt }}" class="logo-img" src="{{ $headerLogoImage }}" loading="lazy">
                            <p class="logo-title">{{ $headerLogoTitle }}</p>
                        </a>
                        @if($__env->hasSection('header_location'))
                            @yield('header_location')
                        @else
                            @include('parts.location')
                        @endif
                        @if($__env->hasSection('header_search'))
                            @yield('header_search')
                        @else
                            @include('parts.searchform')
                        @endif
                        @if($__env->hasSection('header_form'))
                            @yield('header_form')
                        @else
                            @include('parts.form')
                        @endif
                        @if($__env->hasSection('header_theme_toggle'))
                            @yield('header_theme_toggle')
                        @else
                            @include('parts.theme-toggle')
                        @endif
                        @if($__env->hasSection('header_action'))
                            @yield('header_action')
                        @elseif(isset($locale['name']))
                            <a class="tg_link" href="https://t.me/kursivalut_ru_{{ $locale['name'] }}" target="_blank">
                                <img src="/img/tg_logo_white.svg" class="tg_link_img" width="20" height="20" loading="lazy">
                            </a>
                        @endif
                    </div>
                </header>
                @yield('after_header')
                <div class="columns">
                    @section('main')
                    @show

                    @if($showRightColumn)
                        <aside class="right_column">
                            @section('right_column')
                                @include('parts.sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])
                                <div class="additional-info">
                                    <a class="additional-info-a" href="/legal">Правовая информация</a>
                                </div>
                            @show
                        </aside>
                    @endif
                </div>
            </main>
        </div>
        <footer class="footer">
            @section('footer_content')
                @include('parts.footer')
            @show
            @if($showScrollToTop)
                <div id="scroll-to-top" class="scroll-to-top scroll-to-top_inactive">
                    <div id="how-many-new-ads" class="how-many-new-ads how-many-new-ads_inactive"></div>
                </div>
            @endif
            @if($showGdpr)
                @include('parts.gdpr')
            @endif
        </footer>
        @if($showModal)
            @isset($modal_ad)
                <aside class="modal-wrapper modal-active">
                    <div class="popup-bg modal-hidden">
                        <section class="modal-inner selected-post-popup">
                            <div class="close_modal"></div>
                            @isset($modal_ad->id)
                                @include('parts.post', ['ad' => $modal_ad])
                            @else
                                @include('parts.no-posts')
                            @endisset
                        </section>
                    </div>
                </aside>
            @endisset
        @endif
        @if($showPageScripts)
            @section('page_scripts')
                <script>
                    window.ifAllowed = "{{ !empty($geodata['geo_allowed']) }}";
                    window.ifMore = window.ifAllowed ? Math.ceil( Number("{{ $ads_count / 20 }}")) : 0;
                    window.feedStatus = 1;
                    window.currentHeight = 0;
                    window.lastAdTime = "{{ $last_ad_time }}";
                    window.locale = "{{ $locale['name'] }}";
                    window.showRates = "{{ $locale['show_rates'] }}";
                    window.isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    window.h1Keyword = "{{ $locale["h1_keyword"] }}";
                    
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
                        const feed = document.querySelector('#feed');
                        const howManyAdsBlock = document.querySelector("#how-many-new-ads");
                        let url = constructUrl();
                        let favicon = document.querySelector("#favicon");

                        window.howManyNewAds = 0;
                        window.newAdsLastContainer = '';
                           
                        const loadMore = function(occasion = '') {  
                            if(occasion === 'scroll'){
                                if(window.feedStatus >= window.ifMore) return;
                                if( window.currentHeight && window.currentHeight + 1000 > window.pageYOffset ) return;
                                window.currentHeight = window.pageYOffset;
                            } else {
                                let nowTenDigits = Math.floor(Date.now()/1000);
                                const dateRegexp = /date=\d+/;
                                let hoursSinceLastFetch = parseFloat(((nowTenDigits - window.lastAdTime - 10) / 60 / 60).toFixed(4));
                                url = url.replace(/\&offset=\d+/, '');
                                if(dateRegexp.test(url)){
                                    url = url.replace(/[\d\.]+/, 'date='+hoursSinceLastFetch);
                                } else{
                                    if(!url.endsWith("&")) url += "&";
                                    url += 'date='+hoursSinceLastFetch;
                                }
                            }
                            
                            function reqListener () {
                                const item = document.createElement('div');
                                item.innerHTML = this.responseText;
                                if(occasion === 'scroll'){
                                    window.feedStatus++;
                                    url = constructUrl();
                                    feed.appendChild(item);
                                } else {
                                    url = constructUrl();
                                    let blockClassName = "new-ads-" + Date.now();
                                    item.className = "fetched " + blockClassName;
                                    let countNewPosts = item.querySelectorAll(".declaration").length;
                                    if(!!countNewPosts){
                                        window.howManyNewAds += countNewPosts;
                                        howManyAdsBlock.innerText = window.howManyNewAds;
                                        window.newAdsLastContainer = blockClassName;
                                        window.lastAdTime = item.querySelector(".time").dataset.time;
                                        favicon.href = '/img/favicon-dot.svg';
                                        feed.prepend(item);
                                        unstyleNewPosts();
                                    }
                                }
                            }
                            
                            const req = new XMLHttpRequest();
                            req.addEventListener("load", reqListener);
                            req.open("GET", url);
                            req.send();
                        }
                        
                        const unstyleNewPosts = ()=>{
                            let fetched = document.querySelectorAll(".fetched");
                            if(fetched){
                                fetched.forEach(block =>{
                                    let rect = block.getBoundingClientRect();
                                    if(rect.bottom > 150 && !block.classList.contains("fetched-uncolored")){
                                        block.classList.add("fetched-uncolored");
                                        setTimeout(() => block.classList.add("fetched-hidden"), 3500);
                                        let countPosts = block.querySelectorAll(".declaration").length;
                                        window.howManyNewAds -= countPosts;
                                        if(window.howManyNewAds < 0 || !window.howManyNewAds){
                                            window.howManyNewAds = 0;
                                            howManyAdsBlock.classList.add("how-many-new-ads_inactive");
                                            favicon.href = '/img/favicon.svg';
                                        }
                                        howManyAdsBlock.innerText = window.howManyNewAds;
                                    }
                                });
                                
                            }
                        }

                        const scrollToTop = document.querySelector("#scroll-to-top");
                        scrollToTop.addEventListener("click", function(){
                            window.scrollTo({top: 0, left: 0, behavior: "smooth" });
                        });

                        document.addEventListener('scroll', function() {
                            if ( window.pageYOffset + window.screen.height >= feed.scrollHeight) {
                                loadMore('scroll');
                            }
                            if(window.pageYOffset > window.screen.height){
                                scrollToTop.classList.add("scroll-to-top_active");
                                scrollToTop.classList.remove("scroll-to-top_inactive");
                                if(window.howManyNewAds > 0) howManyAdsBlock.classList.remove("how-many-new-ads_inactive");
                            } else{
                                scrollToTop.classList.remove("scroll-to-top_active");
                                howManyAdsBlock.classList.add("how-many-new-ads_inactive");
                            }
                            unstyleNewPosts();
                        });
                        @if( isset($locale['load_freq']) && count($ads) > 0 && !empty($geodata['geo_allowed']))
                        const newAdsFetching = window.setInterval(loadMore, {{ $locale['load_freq'] }}000);
                        @endif
                    });
                </script>
            @show
        @endif
        @include('parts.theme-script')
        @if($showAppJs)
            <script src="/js/app.js?v=@isset($hash){{$hash}}@endisset" defer></script>
        @endif
        @yield('after_scripts')
        @production
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-4ZFLR96373"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'G-4ZFLR96373');
            </script>
            <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();
                for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
            
                ym({{$metrikaId}}, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/{{$metrikaId}}" style="position:absolute; left:-9999px;" alt="Включите JavaScript, пожалуйста" /></div></noscript>
        @endproduction
    </body>
</html>
