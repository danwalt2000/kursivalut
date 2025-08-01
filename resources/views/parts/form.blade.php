<div id="form-bg" class="form-bg">
    <section id="form-wrapper" class="form-wrapper">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="close_modal" class="close_modal"></div>
        @if($is_allowed)
            <p class="h2">Подать объявление</p>
            <form id="ad_form" action="/all?modal=post" method="post" class="form-ad">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-ad-row-radio sell-buy">
                    <input type="radio" id="tosell"
                    name="sellbuy" value="sell" checked>
                    <label for="tosell">Продать</label>
                    <span>|</span>
                    <input type="radio" id="tobuy"
                    name="sellbuy" value="buy">
                    <label for="tobuy">Купить</label>
                </div>
                <div class="form-ad-row datalist">
                    <input id="ad-currencies" list="currencies" name="currency" placeholder="Валюту *" autocomplete="off" required>
                    <label for="ad-currencies">Валюту *</label>
                    <datalist id="currencies">
                        <option value="Доллар">
                        <option value="Евро">
                        <option value="Гривна">
                        <option value="Безнал руб.">
                    </datalist>
                </div>
                <div class="form-ad-row">
                    <input type="number" step="0.01" 
                    maxlength="3" 
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                    name="rate" id="rate" placeholder="Курс *" autocomplete="off" required>
                    <label for="rate">Курс *</label>
                </div>

                <div class="form-ad-row">
                    <input type="text" name="phone" 
                    id="phone" placeholder="Телефон *"
                    oninvalid="setCustomValidity('Допускаются только цифры и символы +-. Длина телефона 7-20 символов')" 
                    oninput="setCustomValidity('')" autocomplete="tel" required>
                    <label for="phone">Телефон *</label>
                </div>
                <div class="form-ad-row">
                    <input type="number" name="sum" id="sum" placeholder="Сумма">
                    <label for="sum">Сумма</label>
                </div>
                <div class="form-ad-row">
                    <input type="text" name="city" id="city" placeholder="В городе" autocomplete="address-level2">
                    <label for="city">В городе</label>
                </div>
                <div class="form-ad-row">
                    <textarea id="textarea" name="textarea" rows="4" cols="20" autocomplete="off"
                    placeholder="Продам 1000 долларов{{$locale["h1_keyword"]}}"></textarea>
                    <label id="textarea_label" class="textarea_label" for="textarea">Продам 1000 долларов{{$locale["h1_keyword"]}}</label>
                </div>
                <div class="form-ad-row">
                    <input id="ad_form_submit" class="form-ad-submit" type="submit" value="Опубликовать">
                </div>
            </form>
        @else
            <h2 class="form-congrats">@if($submit_msg != '') {{ $submit_msg }} @endif</h2>
            <p class="form-congrats-para">Следующее объявление можно подать через {{ $next_submit }}</p>
        @endif
    </section>
</div>
@if( isset($geodata) && !empty($geodata['geo_allowed']) )
    <button id="form-open" class="form-open">
        <span class="form-open-span">+</span>
        Подать объявление
    </button>
@endif