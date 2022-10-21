<section class="form-wrapper">
    @if($is_allowed)
        <h2>Подать объявление</h2>
        <form id="ad_form" action="/" method="post" class="form-ad">
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
                <input id="ad-currencies" list="currencies" name="currency" placeholder="Валюту *" required>
                <label for="currency">Валюту *</label>
                <datalist id="currencies">
                    <option value="Доллар">
                    <option value="Евро">
                    <option value="Гривна">
                    <option value="Безнал руб.">
                </datalist>
            </div>
            <div class="form-ad-row">
                <input type="number" step="0.01" name="rate" id="rate" placeholder="Курс *">
                <label for="rate">Курс *</label>
            </div>

            <div class="form-ad-row">
                <input type="text" name="phone" 
                id="phone" placeholder="Телефон *"
                oninvalid="setCustomValidity('Допускаются только цифры и символы +-. Длина телефона 7-20 символов')" 
                oninput="setCustomValidity('')" >
                <label for="phone">Телефон *</label>
            </div>
            <div class="form-ad-row">
                <input type="number" name="sum" id="sum" placeholder="Сумма">
                <label for="sum">Сумма</label>
            </div>
            <div class="form-ad-row">
                <input type="text" name="city" id="city" placeholder="В городе">
                <label for="city">В городе</label>
            </div>
            <div class="form-ad-row">
                <textarea id="ad-text" name="ad-text" rows="5" cols="33" 
                placeholder="Продам 1000 долларов в Донецке"></textarea>
                <label for="ad-text">Текст объявления</label>
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