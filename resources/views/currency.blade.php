@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav_wrapper">
            <h1>{{ $h1 }}</h1>
            
            @if($path['desc'] && !empty($ads) )
                <p class="hint_message">{{$path['desc']}}</p>
                {{-- @if($path['hint'] )
                    <p class="hint_submessage">{{$path['hint']}}</p>
                @endif --}}
            @endif
            
            @if( !empty($ads) )
                <p class="ads_found">Найдено объявлений {{ $ads_count }}</p>
            @endif
        </header>
        
        <section id="feed" class="feed">
            @include('parts.feed', ['ads' => $ads])
        </section>
    </div>
@stop