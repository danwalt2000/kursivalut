@extends('errors.base')

@section('title', '406. Not Acceptable')
 
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