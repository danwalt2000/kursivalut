@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav">
            <h1>Поиск результатов по запросу: "{{ $search }}"</h1>
            <a class="back-home" href="/">Ко всем объявлениям</a>
            @include('sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])    
        </header>
        
        <section id="feed" class="feed">
            @include('feed', ['ads' => $ads])
        </section>
    </div>
@stop