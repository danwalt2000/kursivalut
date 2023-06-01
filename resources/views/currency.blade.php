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

        @if( !empty($rates) )
        <aside class="rates">
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
                {{-- <div class="rates_table_stock">
                    <div class="rates_table_title">Биржевые котировки</div>
                    @foreach($stock_rates as $rate)
                        @include('parts.rates-loop', ['rate' => $rate])    
                    @endforeach
                </div> --}}
            </section>
        </aside>
        @endif
        
        <section id="feed" class="feed">
            @include('parts.feed', ['ads' => $ads])
        </section>
    </div>
@stop