<div class="rates_table_currency">
    <div class="rates_table_name">{{$rate->symbol}}/RUB</div>
    <div class="rates_table_rate">
        {{$rate->average}}
        <span class="changes-span
        @if($rate->changes > 0)changes-span-plus
        @elseif($rate->changes < 0)changes-span-minus
        @endif
        ">{{$rate->changes}}</span>
    </div>
</div>