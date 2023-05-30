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
            <h2>Курсы валют на наличном рынке {{$locale["h1_keyword"]}} на сегодня</h2>
            <section class="rates_table">
                @foreach($rates as $rate)
                <div class="rates_table_currency">
                    <div class="rates_table_name">{{$rate->name}}</div>
                    <div class="rates_table_name">{{$rate->avg}}</div>
                </div>
                @endforeach
            </section>
        </aside>
        @endif
        
        <section id="feed" class="feed">
            @include('parts.feed', ['ads' => $ads])
        </section>
    </div>
@stop