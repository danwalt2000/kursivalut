<span id="open-search" class="search open-search">
    <svg alt="Поиск" class="search" width="18" height="18"><use href="/img/search.svg"></use></svg>
</span>
<form action="{{ url("s") }}" class="search-form" method="get">
    <input id="search" type="text" placeholder="Поиск в объявлениях" name="search" value=""
        class="search-input border-border{{ isset($errors) && $errors->has('search') ? ' is-invalid' : '' }}" minlength="1" maxlength="30" required>
    <label class="search-label" for="search">Поиск в объявлениях</label>
    <button id="search-submit" type="submit" class="search-submit" title="Поиск">
        <svg alt="Поиск" class="search" width="18" height="18"><use href="/img/search.svg"></use></svg>
    </button>  
</form>
