@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav">
            <h1>{{ $h1 }}</h1>
            <nav class="nav-wrapper">
                <span class="chip @if( $path['sell_buy'] == 'all' || $path['sell_buy'] == '' ) chip-active @endif"><a href="/?{{ $path['query'] }}">Все</a></span>
                <ul class="sell-buy @if($path['sell_buy'] != 'all') sell-buy-active @endif">
                    <li class="@if($path['sell_buy'] == 'sell') tab-active @endif">
                        <a href="/ads/sell/{{ $path['currency'] }}?{{ $path['query'] }}">Продажа</a>
                    </li>
                    <span>|</span>
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
                            <li><a href="/ads/{{ $path['sell_buy'] }}?{{ $path['query'] }}">Все валюты</a></li>
                        @endif

                        @foreach($currencies as $name => $title)
                            {{-- Выбранную валюту в списке не показываем--}}
                            @if($path["currency"] != '' && $currencies[$path["currency"]] == $title )
                                @continue
                            @endif

                            <li><a href="/ads/{{ $path['sell_buy'] }}/{{ $name }}?{{ $path['query'] }}">{{ $title }}</a></li>
                        @endforeach
                    </div>
                </ul>
            </nav>
        </header>
        
        <section id="feed" class="feed">
            @include('feed', ['ads' => $ads])
        </section>
    </div>
@stop