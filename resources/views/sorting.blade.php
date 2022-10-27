<ul class="chips">
    <span class="h3">За:</span>
    @foreach($date_sort as $time => $title)
        <li class="chip @if($time == $path['hours']) chip-active @endif">
            <a href="{{ request()->fullUrlWithQuery(['date' => $time]) }} ">{{$title}}</a>
        </li>
    @endforeach
</ul>
<ul class="sort chips">
    <span class="h3">Сортировать по:</span>
    <li class="chip with-arrow with-arrow-rarr @if("date_desc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'desc']) }}">
            Дате
        </a>
    </li>
    <li class="chip with-arrow with-arrow-rarr with-arrow-rarr-up @if("date_asc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'asc']) }}">
            Дате
        </a>
    </li>
    <li class="chip with-arrow with-arrow-rarr @if("popularity_desc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity', 'order' => 'desc']) }}">
            Популярности
        </a>
    </li>
    {{-- <li class="chip"><a href="{{ request()->fullUrlWithQuery(['sort' => 'rate', 'order' => 'asc']) }}">Курсу</a></li> --}}
</ul>
@if(count($ads))
    <p>Найдено объявлений {{ $ads_count }}</p>
@endif