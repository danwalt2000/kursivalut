<ul class="currencies">
    <span class="h6 mr-10">За:</span>
    {{-- Выбранный диапазон отображаем первым --}}

    <li id="selected-currency" class="with-arrow">
        {{ $date_sort[ $path['hours'] ] }}
    </li>
    <div id="currencies-hidden" class="currencies-hidden">
        @foreach($date_sort as $time => $title)
            {{-- Выбранный диапазон в списке не показываем --}}
            @if($time == $path['hours'] )
                @continue
            @endif

            <li><a href="{{ request()->fullUrlWithQuery(['date' => $time]) }} ">{{$title}}</a></li>
        @endforeach
    </div>
</ul>

<ul class="sort chips">
    <span class="h6 mr-10">Сортировать по:</span>
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
    <p class="ads_found">Найдено объявлений {{ $ads_count }}</p>
@endif