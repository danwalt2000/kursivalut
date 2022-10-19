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
                    <a href="/"><img class="logo-img" src="/img/pig.svg" ></a>
                    {{-- {{ dd(url("currency")) }} --}}
                    <p class="logo-title">Обмен валют</p>
                    <form action="{{ url("s") }}" class="search-form" method="get">
                        <input id="search" type="text" placeholder="Поиск в объявлениях" name="search" value=""
                            class="search-input @error('search') is-invalid @enderror" required>
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
                            <form action="" method="get" class="form-ad">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                {{-- <p class="form-ad-para">Хочу</p> --}}
                                <div class="form-ad-row-radio sell-buy">
                                    <input type="radio" id="tosell"
                                    name="contact" value="sell" checked>
                                    <label for="tosell">Продать</label>
                                    <span>|</span>
                                    <input type="radio" id="tobuy"
                                    name="contact" value="buy">
                                    <label for="tobuy">Купить</label>
                                </div>
                                <div class="form-ad-row datalist">
                                    <input list="currencies" name="currency" placeholder="Валюту *">
                                    <label for="currency">Валюту *</label>
                                    <datalist id="currencies">
                                        <option value="Доллар">
                                        <option value="Евро">
                                        <option value="Гривну">
                                        <option value="Безнал руб.">
                                    </datalist>
                                </div>

                                <div class="form-ad-row">
                                    <input type="text" name="name" id="name" placeholder="Сумма">
                                    <label for="name">Сумма</label>
                                </div>
                                <div class="form-ad-row">
                                    <input type="email" name="email" id="email" placeholder="В городе">
                                    <label for="email">В городе</label>
                                </div>
                                <div class="form-ad-row">
                                    <textarea id="story" name="story" rows="5" cols="33" 
                                    placeholder="Хочу продать 1000 долларов в Донецке"></textarea>
                                    <label for="story">Текст объявления</label>
                                </div>
                                <div class="form-ad-row">
                                    <input class="form-ad-submit" type="submit" value="Опубликовать">
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
