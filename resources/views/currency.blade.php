@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav_wrapper">
            <h1>{{ $h1 }}</h1>
            <section class="header-nav">
                <nav class="nav-wrapper sell_buy">
                    <span class="chip @if( $path['sell_buy'] == 'all' || $path['sell_buy'] == '' ) chip-active @endif"><a href="/{{$query}}">Все</a></span>
                    <li class="chip @if($path['sell_buy'] == 'sell') chip-active @endif">
                        <a href="/ads/sell/{{ $path['currency'] }}{{$query}}">Продажа</a>
                    </li>
                    <li class="chip @if($path['sell_buy'] == 'buy') chip-active @endif">
                        <a href="/ads/buy/{{ $path['currency'] }}{{$query}}">Покупка</a>
                    </li>
                </nav>
                <nav class="nav-wrapper nav-currencies">
                    @foreach($currencies as $name => $title)
                        <li class="chip @if($path["currency"] != '' && $currencies[$path["currency"]] == $title) chip-active @endif">
                            <a href="/ads/{{ $path['sell_buy'] }}/{{ $name }}{{$query}}">{{ $title }}</a>
                        </li>
                        {{-- Выбранную валюту в списке не показываем--}}
                    @endforeach
                </nav>
                @include('sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])    
            </section>
            @if($path['hint'])
                <p class="hint_message">{{$path['hint']}}</p>
            @endif
        </header>
        
        <section id="feed" class="feed">
            @include('feed', ['ads' => $ads])
        </section>
    </div>
@stop