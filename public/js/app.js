let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function getPhone( arr ){
    let phoneBlock = event.target;
    if( phoneBlock.classList.contains("hidden_phone-visible") ) return;

    let adContent = phoneBlock.parentNode.innerHTML.replace(" <button", "&#32;<button").replace("</button> ", "</button>&#32;");
    let postObj = { 
        postId: arr[0], 
        phoneIndex: arr[1],
        content: adContent
    }
    let post = JSON.stringify(postObj);

    const url = "/ajax";
    let xhr = new XMLHttpRequest()
    
    xhr.open('POST', url, true)
    xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.send(post);
    
    xhr.onload = function () {
        if(xhr.status === 200) {
             if( !phoneBlock.classList.contains("go_to_vk")){
                phoneBlock.classList.add("hidden_phone-visible");
                phoneBlock.innerText = xhr.response;
             }             
        }
    }
}
window.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    const modalParam = url.searchParams.get("modal");
    // форма
    const adForm = document.querySelector("#ad_form");
    const adFormSubmit = document.querySelector("#ad_form_submit");
    const adFormText = document.querySelector("#textarea");
    const adFormTextLabel = document.querySelector("#textarea_label");
    const adFormToSell = document.querySelector("#tosell");
    const adFormToBuy = document.querySelector("#tobuy");
    const adFormRate = document.querySelector("#rate");
    const adFormPhone = document.querySelector("#phone");
    const adFormCurrency = document.querySelector("#ad-currencies");
    const adFormSum = document.querySelector("#sum");
    const adFormCity = document.querySelector("#city");
    
    const changePlaceholder = ()=>{
        let sellBuy = adFormToSell.checked ? "Продам " : "Куплю ";
        let currency = adFormCurrency.value ? adFormCurrency.value.toLowerCase() + " "  : "валюту ";
        if(currency === "гривна ") currency = "гривну "; // склоняем слово "гривна"
        let rate = adFormRate.value ? "по курсу " + adFormRate.value : "";
        let sum = adFormSum.value ? " в сумме " + adFormSum.value : '';
        let city = adFormCity.value ? " в городе " + adFormCity.value : "";
        let phone = adFormPhone.value ? ". Мой номер: " + adFormPhone.value  + "." : "";
        let text = sellBuy + currency + rate + sum + city + phone;
        
        window.placeholder = text;
        adFormTextLabel.innerText = window.placeholder;
        if(!adFormText.value){
            adFormText.placeholder = text;
        }
    }

    const changeTextarea = ()=>{
        if(adFormText.value){
            adFormText.classList.add("textarea_active");
        } else{
            adFormText.classList.remove("textarea_active");
        }
    }

    const submitForm = (e)=>{
        if(!adFormText.value){
            adFormText.value = window.placeholder;
        } else{
            adFormText.value =  window.placeholder + "&nbsp;" + adFormText.value;
        }
        formBg.classList.remove("form-bg-open");
    }

    if(adForm){
        adForm.addEventListener("submit", submitForm, false);

        adFormToSell.addEventListener('change', changePlaceholder);
        adFormToBuy.addEventListener('change', changePlaceholder);
        adFormCurrency.addEventListener('change', changePlaceholder);
        adFormRate.addEventListener('change', changePlaceholder);
        adFormPhone.addEventListener('change', changePlaceholder);
        adFormSum.addEventListener('change', changePlaceholder);
        adFormCity.addEventListener('change', changePlaceholder);
        adFormText.addEventListener('input', changeTextarea);
    }
    
    // управление выпадающми списками
    const dropButtons = document.querySelectorAll(".dropdown-item");
    
    const checkDropdown = (event)=>{
        let dropButton = event.target.closest(".dropdown-item");
        let dropLists = document.querySelectorAll(".dropdown-hidden");
        needToOpen = false;

        if(dropButton) needToOpen = !dropButton.classList.contains("currencies-active");

        dropButtons.forEach( dropButton => dropButton.classList.remove("currencies-active") );
        dropLists.forEach( dropList => dropList.classList.remove("active") );
        document.removeEventListener("click", checkDropdown);

        if(needToOpen){
            let dropMenu = event.target.closest(".dropdown-menu");
            dropMenu.querySelector(".dropdown-hidden").classList.add("active");
            dropButton.classList.add("currencies-active");
            document.addEventListener("click", checkDropdown);
        }
        event.stopPropagation();
    }
    const toggleDropdown = (event)=>{
        checkDropdown(event); 
    }

    if( dropButtons ){
        dropButtons.forEach( (dropButton)=>{
            dropButton.addEventListener("click", toggleDropdown, false);
        } );
    }

    const formOpen = document.querySelector("#form-open");
    const formBg = document.querySelector("#form-bg");
    const formWrapper = document.querySelector("#form-wrapper");

    // если есть параметр modal=post, значит нужно открыть модальное окно формы
    if( modalParam && modalParam == "post") formBg.classList.add("form-bg-open");
    if(formOpen){
        formOpen.addEventListener("click", function(){
            formBg.classList.add("form-bg-open");
        });
        formBg.addEventListener('click', function (event) {
            if ( !formWrapper.contains(event.target) || event.target.id === "close_modal" ) {
                formBg.classList.remove("form-bg-open");
                // при наличии модального окна, отправляем на главную
                if(modalParam) location.href = '/';
            }
        });
    }

    // управление модальными окнами
    const closeModal = (event)=>{
        let modalInner = document.querySelector('.modal-inner');
        if ( !modalInner.contains(event.target) || event.target.classList.contains("close_modal") ) {
            document.querySelectorAll('.modal-wrapper').forEach(
                wrapper => wrapper.classList.remove('modal-active')
            );
            document.removeEventListener('click', closeModal);
        }
    }
    const openModal = (event)=>{
        event.stopPropagation();
        event.target.parentElement.classList.add('modal-active');
        document.addEventListener('click', closeModal);
    }    
    const modalButtons = document.querySelectorAll('.modal-open');
    modalButtons.forEach( modalButton =>{
        modalButton.addEventListener('click', openModal);
    });

    const openHintBtn = document.querySelector(".open-hint-btn");
    const openHintMessage = document.querySelector(".open-hint-message");
    const hideHint = ()=>{
        openHintBtn.classList.remove("open-hint-btn_active");
        openHintMessage.classList.remove("open-hint-message_active");
    }

    if(!!openHintBtn){
        const closeHint = document.querySelector(".open-hint-btn-close");

        openHintBtn.addEventListener("click", e =>{
            let opened = openHintMessage.classList.contains("open-hint-message_active");
            
            if(!opened){
                document.addEventListener("click", hideHint);
                e.target.classList.add("open-hint-btn_active");
                openHintMessage.classList.add("open-hint-message_active");
                e.stopPropagation();
            } 
        });
        closeHint.addEventListener("click", e =>{
            openHintBtn.classList.remove("open-hint-btn_active");
            openHintMessage.classList.remove("open-hint-message_active");
        });
    }
    
    const openSearch = document.querySelector("#open-search");
    openSearch.addEventListener("click", function(){
        openSearch.classList.toggle("open-search-active");
    });
    
    const filtersButton = document.querySelector("#open-filters");
    const filters = document.querySelector(".sorting-column");
    const toggleFilters = ()=>{
        if(filters.classList.contains("sorting-column-collapsed")){
            filters.classList.remove("sorting-column-collapsed");
        } else{
            filters.classList.add("sorting-column-collapsed");
        }
    }
    if(filtersButton) filtersButton.addEventListener("click", toggleFilters );

});