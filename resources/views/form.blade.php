<section class="form-wrapper">
    <h2>Подать объявление</h2>
    <form id="ad_form" action="/" method="post" class="form-ad">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-ad-row-radio sell-buy">
            <input type="radio" id="tosell"
            name="sellbuy" value="sell">
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
                <option value="Гривну">
                <option value="Безнал руб.">
            </datalist>
        </div>
        <div class="form-ad-row">
            <input type="text" name="rate" id="rate" placeholder="Курс *">
            <label for="rate">Курс *</label>
        </div>

        <div class="form-ad-row">
            <input type="text" name="sum" id="sum" placeholder="Сумма">
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
</section>