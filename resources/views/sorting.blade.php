<section class="sorting-column sorting-column-collapsed">
    <h2 class="sorting-h2">Фильтры</h2>
    <nav class="show-all-row">
        <span class="chip @if( $path['sell_buy'] == 'all' || $path['sell_buy'] == '' ) chip-active @endif"><a href="/{{$query}}">Показать все</a></span>
        @if( !empty($ads) )
        <div class="course_checkbox">
            @if("true" == $path['rate'])
                <a href="{{ request()->fullUrlWithQuery(['rate' => 'false']) }}" title="Показать все объявления">
                    <div class="checkbox checkbox_active"></div>
            @else
                <a href="{{ preg_replace('/\?$/', '', request()->fullUrlWithQuery(['rate' => null])) }}" title="Показать только объявления, в которых есть курс">
                    <div class="checkbox"></div>
            @endif
            Только с курсом</a>
        </div>
    @endif
    </nav>
    <nav class="nav-wrapper sell_buy">
        <li class="chip @if($path['sell_buy'] == 'sell') chip-active @endif">
            <a href="/ads/sell/{{ $path['currency'] }}{{$query}}">Продажа</a>
        </li>
        <li class="chip @if($path['sell_buy'] == 'buy') chip-active @endif">
            <a href="/ads/buy/{{ $path['currency'] }}{{$query}}">Покупка</a>
        </li>
    </nav>
    <div class="currency-title">Валюта</div>
    <nav class="nav-wrapper nav-currencies">
        @foreach($currencies as $name => $title)
            <li class="chip @if($path["currency"] != '' && $currencies[$path["currency"]] == $title)chip-active @endif">
                <a href="/ads/{{ $path['sell_buy'] }}/{{ $name }}{{$query}}">{{ $title }}</a>
            </li>
            {{-- Выбранную валюту в списке не показываем--}}
        @endforeach
    </nav>
    <ul class="currencies">
        <span class="h6">За последние:</span>
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
    
                @if (24 ==  $time)
                    <li><a href="{{ preg_replace('/\?$/', '', request()->fullUrlWithQuery(['date' => null]) ) }}">{{$title}}</a></li>
                @else
                    <li><a href="{{ request()->fullUrlWithQuery(['date' => $time]) }}">{{$title}}</a></li>
                @endif
            @endforeach
        </div>
    </ul>
    
    <ul class="sort chips">
        <span class="h6">Сортировка</span>
        <li class="chip with-arrow with-arrow-rarr @if("date_desc" == $path['sort']) chip-active @endif">
            <a href="{{ preg_replace('/\?$/', '', request()->fullUrlWithQuery(['sort' => null, 'order' => null]) ) }}" title="Сортировать от нового к старому">
                Дата
            </a>
        </li>
        <li class="chip with-arrow with-arrow-rarr with-arrow-rarr-up @if("date_asc" == $path['sort']) chip-active @endif">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'asc']) }}" title="Сортировать от старого к новому">
                Дата
            </a>
        </li>
        {{-- <li class="chip with-arrow with-arrow-rarr @if("popularity_desc" == $path['sort']) chip-active @endif">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity', 'order' => 'desc']) }}" title="Сортировать по убыванию популярности">
                Популярности
            </a>
        </li> --}}
    </ul>
   
</section>
