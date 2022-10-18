<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="bg-gradient">

            <main class="main">
                <div class="logo">
                    <p class="text-gray-500">Обмен валют</p>
                    <form action="/s" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях"
                            class="search-input @error('search') is-invalid @enderror">
                        <button type="submit" class="search-submit">
                            <img class="search" src="/img/search.svg" alt="" width="18" height="18">
                        </button>  
                    </form>
                </div>
                
                <div class="columns">
                    <div class="left_column">
                        <header class="header-nav">
                            <h1>
                                @if( $path['sell_buy'] == 'sell')
                                    Купить
                                @elseif( $path['sell_buy'] == 'buy')
                                    Продать
                                @else
                                    Продать/купить
                                @endif
                                {{-- {{ dd($path["currency"])}} --}}
                                @foreach($currencies as $name => $title)
                                    @if($path["currency"] == '')
                                        все валюты
                                        @break
                                    @endif
                                    @if($path["currency"] == $name )
                                        <span class="lowercased">    
                                            @if($title == "Гривна ₴")
                                                Гривну ₴
                                            @else
                                                {{ $title }}
                                            @endif
                                        </span>
                                    @endif
                                @endforeach
                                в Донецке
                            </h1>
                            <nav class="nav-wrapper">
                                <span class="chip @if( $path['sell_buy'] == 'all' || $path['sell_buy'] == '' ) chip-active @endif"><a href="/?{{ $path['query'] }}">Все</a></span>
                                <ul class="sell-buy @if($path['sell_buy'] != 'all') sell-buy-active @endif">
                                    <li class="@if($path['sell_buy'] == 'sell') tab-active @endif">
                                        {{-- {{ dd(Request::get('date')) }} --}}
                                        <a href="/ads/sell/{{ $path['currency'] }}?{{ $path['query'] }}">Продажа</a>
                                    </li>
                                    <li class="@if($path['sell_buy'] == 'buy') tab-active @endif">
                                        <a href="/ads/buy/{{ $path['currency'] }}?{{ $path['query'] }}">Покупка</a>
                                    </li>
                                </ul>
                                <ul class="currencies">
                                    {{-- Выбранную валюту отображаем первой --}}
                                    <li id="selected-currency" class="with-arrow">
                                        @if($path["currency"] != '' )
                                            {{ $currencies[$path["currency"]] }}
                                        @else 
                                            Все валюты
                                        @endif
                                    </li>
                                    <div id="currencies-hidden" class="currencies-hidden">
                                        @if($path["currency"] != '')
                                            <li><a href="/ads/{{ $path['sell_buy'] }}/">Все валюты</a></li>
                                        @endif

                                        @foreach($currencies as $name => $title)
                                            {{-- Выбранную валюту в списке не показываем--}}
                                            @if($path["currency"] != '' && $currencies[$path["currency"]] == $title )
                                                @continue
                                            @endif

                                            <li><a href="/ads/{{ $path['sell_buy'] }}/{{ $name }}">{{ $title }}</a></li>
                                        @endforeach
                                    </div>
                                </ul>
                            </nav>
                            <ul class="chips">
                                <h3>За:</h3>
                                @foreach($date_sort as $time => $title)
                                    <li class="chip @if($time == $path['hours']) chip-active @endif">
                                        <a href="{{ request()->fullUrlWithQuery(['date' => $time]) }} ">{{$title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="sort chips">
                                <h3>Сортировать по:</h3>
                                <li class="chip with-arrow with-arrow-rarr @if("date_desc" == $path['sort']) chip-active @endif">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'desc']) }}">
                                        Дате
                                    </a>
                                </li>
                                <li class="chip with-arrow with-arrow-rarr with-arrow-rarr-up @if("date_asc" == $path['sort']) chip-active @endif">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'asc']) }}">
                                        Дате
                                    </a>
                                </li>
                                <li class="chip with-arrow with-arrow-rarr @if("popularity_desc" == $path['sort']) chip-active @endif">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity', 'order' => 'desc']) }}">
                                        Популярности
                                    </a>
                                </li>
                                {{-- <li class="chip"><a href="{{ request()->fullUrlWithQuery(['sort' => 'rate', 'order' => 'asc']) }}">Курсу</a></li> --}}
                            </ul>
                            @if($ads)
                                <p>Найдено объявлений {{ $ads_count }}</p>
                            @endif
                        </header>
                        
                        <section class="feed">
                            @forelse($ads as $ad)
                                <article class="post">
                                    <header class="post-header">
                                        <div class="info">
                                            <img src="/img/groups/{!! str_replace('-', '', $ad->owner_id) !!}.jpg">
                                            <a class="text-gray-600" href="{{ $ad->link }}" target="_blank" rel="nofollow noopener noreferrer">
                                                {{ gmdate("H:i d.m.Y", ($ad->date + 3 * 60 * 60)) }}
                                            </a>
                                        </div>
                                        <svg class="three-dots" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="more_horizontal_24__Page-2" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="more_horizontal_24__more_horizontal_24"><path id="more_horizontal_24__Bounds" d="M24 0H0v24h24z"></path><path d="M18 10a2 2 0 0 1 2 2 2 2 0 0 1-2 2 2 2 0 0 1-2-2c0-1.1.9-2 2-2Zm-6 4a2 2 0 0 1-2-2c0-1.1.9-2 2-2a2 2 0 0 1 2 2 2 2 0 0 1-2 2Zm-6 0a2 2 0 0 1-2-2c0-1.1.9-2 2-2a2 2 0 0 1 2 2 2 2 0 0 1-2 2Z" id="more_horizontal_24__Mask" fill="currentColor"></path></g></g></svg>
                                    </header>
                                    
                                    <span class="">{!! $ad->content_changed !!}</span>
                                </article>
                            @empty
                                <h2 class="">Объявлений не найдено</h2>
                            @endforelse
                        </section>
                    </div>
                    <div class="right_column">
                        <h2>Подать объявление</h2>
                    </div>
                </div>
            </main>
        </div>
        <script src="/js/app.js" defer></script>
    </body>
</html>
