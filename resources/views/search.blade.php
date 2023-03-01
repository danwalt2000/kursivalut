@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav_wrapper">
            <h1>Поиск результатов по запросу: "{{ $search }}"</h1>
            <section class="header-nav">
                <a class="back-home" href="/">Ко всем объявлениям</a>
            </section>
        </header>
        
        <section id="feed" class="feed">
            @include('feed', ['ads' => $ads])
        </section>
    </div>
@stop