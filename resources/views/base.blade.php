<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <meta name="robots" content="noyaca">
        <meta name="description" content="Агрегатор объявлений о покупке и продаже валюты">
        <meta name="keywords" content="купить валюту, купить доллар, купить евро, купить гривну, купить рубль, купить безнал, продать валюту, продать доллар, продать евро, продать гривну, продать безнал, Донецк, купить доллар в ДНР, купить доллар в Макеевке, Горловка" />

        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="Агрегатор объявлений о покупке и продаже валюты">
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="bg-gradient">

            <main class="main">
                <div class="logo">
                    <img class="logo-img" src="/img/pig.svg" >
                    {{-- {{ dd(url("currency")) }} --}}
                    <p class="logo-title">Обмен валют</p>
                    <form action="{{ url("s") }}" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях" value=""
                            class="search-input @error('search') is-invalid @enderror">
                        <button type="submit" class="search-submit">
                            <img class="search" src="/img/search.svg" alt="" width="18" height="18">
                        </button>  
                    </form>
                </div>
                <div class="columns">
                    @section('main')
                    @show

                    <div class="right_column">
                        <section class="form-wrapper">
                            <h2>Подать объявление</h2>
                            <form action="" method="get" class="form-example">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-example">
                                    <label for="name">Enter your name: </label>
                                    <input type="text" name="name" id="name" required>
                                </div>
                                <div class="form-example">
                                    <label for="email">Enter your email: </label>
                                    <input type="email" name="email" id="email" required>
                                </div>
                                <label for="story">Tell us your story:</label>
                                <textarea id="story" name="story" rows="5" cols="33">
                                    It was a dark and stormy night...
                                </textarea>
                                <div class="form-example">
                                    <input type="submit" value="Subscribe!">
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </main>
        </div>
        <script src="/js/app.js" defer></script>
    </body>
</html>
