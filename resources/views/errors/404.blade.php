@extends('errors.base')

@section('title', '404. Страница не найдена')
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav">
            <h1>@yield('title')</h1>
            <a class="back-home" href="/">На главную</a>
        </header>
    </div>
@stop