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

            <li><a href="{{ request()->fullUrlWithQuery(['date' => $time]) }}">{{$title}}</a></li>
        @endforeach
    </div>
</ul>

<ul class="sort chips">
    <span class="h6 mr-10">Сортировать по:</span>
    <li class="chip with-arrow with-arrow-rarr @if("date_desc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'desc']) }}" title="Сортировать от нового к старому">
            Дате
        </a>
    </li>
    <li class="chip with-arrow with-arrow-rarr with-arrow-rarr-up @if("date_asc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'asc']) }}" title="Сортировать от старого к новому">
            Дате
        </a>
    </li>
    <li class="chip with-arrow with-arrow-rarr @if("popularity_desc" == $path['sort']) chip-active @endif">
        <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity', 'order' => 'desc']) }}" title="Сортировать по убыванию популярности">
            Популярности
        </a>
    </li>
    {{-- <li class="chip"><a href="{{ request()->fullUrlWithQuery(['sort' => 'rate', 'order' => 'asc']) }}">Курсу</a></li> --}}
</ul>
@if(count($ads))
    <p class="ads_found">Найдено объявлений {{ $ads_count }}</p>
    @if($path['currency'])
        <div class="course_checkbox">
            @if("true" == $path['rate'])
                <a href="{{ request()->fullUrlWithQuery(['rate' => 'false']) }}" title="Показать все объявления">
                    <div class="checkbox checkbox_active"></div>
            @else
                <a href="{{ request()->fullUrlWithQuery(['rate' => 'true']) }}" title="Показать только объявления, в которых есть курс">
                    <div class="checkbox"></div>
            @endif
            Только с курсом</a>
        </div>
    @endif
@endif