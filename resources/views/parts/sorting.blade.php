<section class="sorting-column sorting-column-collapsed">
    <div id="open-filters" class="open-filters"><img alt="Открыть фильтры" width="15" height="8" src="/img/arrow-down-cyan.svg"></div>
    <h2 class="sorting-h2">Фильтры</h2>
    <nav class="show-all-row">
        <span class="chip @if( $path['sell_buy'] == 'all' || $path['sell_buy'] == '' ) chip-active @endif"><a href="/{{$query}}">Показать все</a></span>
        @if( !empty($ads) )
        <div class="course_checkbox">
            @if("true" == $path['rate'])
                <a href="{{ request()->fullUrlWithQuery(['rate' => 'false']) }}" title="Показать в том числе объявления без курса">
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
    @if(count($currencies) > 3)<div class="currency-title">Валюта</div>@endif
    <nav class="nav-wrapper nav-currencies">
        @foreach($currencies as $name => $title)
            <li class="chip @if($path["currency"] != '' && $currencies[$path["currency"]] == $title)chip-active @endif">
                <a href="/ads/{{ $path['sell_buy'] }}/{{ $name }}{{$query}}">{{ $title }}</a>
            </li>
            {{-- Выбранную валюту в списке не показываем--}}
        @endforeach
    </nav>
    <ul class="currencies dropdown-menu">
        <span class="h6">За время</span>
        {{-- Выбранный диапазон отображаем первым --}}
    
        <li class="with-arrow dropdown-item">
            {{ $date_sort[ $path['hours'] ] }}
        </li>
        <div class="currencies-hidden dropdown-hidden">
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
    
    <ul class="sort dropdown-menu">
        <span class="h6">Сортировка</span>
    
        <li class="with-arrow dropdown-item">
            <span class="with-arrow-rarr with-arrow-rarr-span @if("date_asc" == $path['sort']) with-arrow-rarr-up @endif">Дата</span>
        </li>
        <div class="dropdown-hidden">
            <li class="dropdown-item with-arrow-rarr @if("date_desc" == $path['sort']) with-arrow-rarr-up @endif"><a 
                @if("date_desc" == $path['sort'])
                    href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'order' => 'asc']) }}"
                @else
                    href="{{ preg_replace('/\?$/', '', request()->fullUrlWithQuery(['sort' => null, 'order' => null]) ) }}"
                @endif >Дата</a>
        </div>
    </ul>
   
</section>
