@extends('base')
 
@section('title', $h1)
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav">
            <h1>Поиск результатов по запросу: "{{ $search }}"</h1>
            @include('sorting', ['date_sort' => $date_sort, 'path' => $path, 'ads' => $ads])    
        </header>
        
        @include('feed', ['ads' => $ads])
        
    </div>
@stop