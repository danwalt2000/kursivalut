<section class="form-wrapper">
    <h2>Подать объявление</h2>
    <form action="" method="get" class="form-ad">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-ad-row-radio sell-buy">
            <input type="radio" id="tosell"
            name="contact" value="sell" checked>
            <label for="tosell">Продать</label>
            <span>|</span>
            <input type="radio" id="tobuy"
            name="contact" value="buy">
            <label for="tobuy">Купить</label>
        </div>
        <div class="form-ad-row datalist">
            <input list="currencies" name="currency" placeholder="Валюту *">
            <label for="currency">Валюту *</label>
            <datalist id="currencies">
                <option value="Доллар">
                <option value="Евро">
                <option value="Гривну">
                <option value="Безнал руб.">
            </datalist>
        </div>

        <div class="form-ad-row">
            <input type="text" name="name" id="name" placeholder="Сумма">
            <label for="name">Сумма</label>
        </div>
        <div class="form-ad-row">
            <input type="email" name="email" id="email" placeholder="В городе">
            <label for="email">В городе</label>
        </div>
        <div class="form-ad-row">
            <textarea id="story" name="story" rows="5" cols="33" 
            placeholder="Хочу продать 1000 долларов в Донецке"></textarea>
            <label for="story">Текст объявления</label>
        </div>
        <div class="form-ad-row">
            <input class="form-ad-submit" type="submit" value="Опубликовать">
        </div>
    </form>
</section>