@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav_wrapper">
            <h1>{{ $h1 }}</h1>
            
            @if($path['desc'] && !empty($ads) )
                <p class="hint_message">{{$path['desc']}}</p>
            @endif
            
            @if( !empty($ads) )
                <p class="ads_found">Найдено объявлений {{ $ads_count }}</p>
            @endif
        </header>

        {{-- <aside class="attention-note">
            В связи с участившимися случаями блокировки vk-сообществ, публикующих объявления об обмене валют, наше приложение больше не будет собирать информацию с сайта vk.com. <br>Через наш сайт нельзя публиковать объявления об обмене валют.
        </aside> --}}

        @if( !empty($rates) )
        <aside id="rates" class="rates">
            <div class="rates_title">
                <h2>Курсы валют на сегодня</h2>
            </div>
            <section class="rates_table">
                <div class="rates_table_spot">
                    <div class="rates_table_title">
                        Наличный рынок
                        <div class="open-hint">
                            <span class="open-hint-btn">?</span>
                            <div class="open-hint-message"><span class="open-hint-btn-close"></span>
                                Средние курсы купли-продажи валюты за последние 24 часа
                            </div>
                        </div>
                    </div>
                    @foreach($rates as $rate)
                        @include('parts.rates-loop', ['rate' => $rate])    
                    @endforeach
                </div>
                <div class="rates_table_stock">
                    <div class="rates_table_title">Биржевые котировки</div>
                    @foreach($stock_rates as $rate)
                        @include('parts.rates-loop', ['rate' => $rate])    
                    @endforeach
                </div>
            </section>
        </aside>
        <aside id="chart-block" class="chart chart_hidden">
            <div class="chart_title">
                <h2>График курса <span id="chart_currency" class="chart_currency">доллара</span> к рублю</h2>
            </div>
            <div>
                <canvas id="chart"></canvas>
            </div>
            <div class="chart-nav">
                <div class="chart-dropdown dropdown-menu">
                    <span class="h6">За период:</span>
                
                    <ul class="chart-list">
                        <li class="with-arrow dropdown-item chart-period_active">
                            <span class="with-arrow-rarr" data-period="14">две недели</span>
                        </li>
                        <div class="dropdown-hidden">
                            <li class="dropdown-item chart-period with-arrow-rarr " data-period="7">неделю</li>
                            <li class="dropdown-item chart-period with-arrow-rarr chart-period_hidden" data-period="14">две недели</li>
                            <li class="dropdown-item chart-period with-arrow-rarr" data-period="30">30 дней</li>
                            <li class="dropdown-item chart-period with-arrow-rarr" data-period="180">180 дней</li>
                            <li class="dropdown-item chart-period with-arrow-rarr" data-period="365">365 дней</li>
                        </div>
                    </ul>
                </div>
                <ul class="chart-buttons">
                    @foreach($rates as $rate)
                        <li class="chart-button chart-currency @if($rate->currency == "dollar") chart-currency_active @endif" 
                            data-currency="{{$rate->currency}}" data-title="{{$currencies_loc[$rate->currency]}}">
                            {{$currencies[$rate->currency]}}
                        </li>
                    @endforeach
                </ul>
            </div>
                
                <script src="/js/ratechart.js"></script>
                <script src="/js/libs/chart.js" defer></script>
                {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script> --}}
            
        </aside>
        @endif
        
        <section id="feed" class="feed">
            @include('parts.feed', ['ads' => $ads])
        </section>
    </div>
@stop