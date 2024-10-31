@extends('errors.base')

@section('title', '401. Вход запрещен')
 
@section('main')
    @parent
    <div class="left_column">
        <header class="header-nav_wrapper">
            <h1>@yield('title')</h1>
            <section class="header-nav">
                <a class="back-home" href="/">На главную</a>
            </section>
        </header>
    </div>
@stop