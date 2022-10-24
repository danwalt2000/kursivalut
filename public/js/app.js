let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const linksToVk = Array.from(document.querySelectorAll(".go_to_vk"));

function getPhone( arr, phoneOrLink = "phone" ){
    let phoneBlock = event.target;
    let postObj = { 
        postId: arr[0], 
        phoneIndex: arr[1],
        phoneOrLink: phoneOrLink
    }
    let post = JSON.stringify(postObj)

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
    // форма
    const adForm = document.querySelector("#ad_form");
    const adFormSubmit = document.querySelector("#ad_form_submit");
    const adFormText = document.querySelector("#ad-text");
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
        let phone = adFormPhone.value ? ". Мой номер: " + adFormPhone.value : "";
        let text = sellBuy + currency + rate + sum + city + phone;
        

        if(!adFormText.value){
            adFormText.placeholder = text;
        }
    }
    const submitForm = (e)=>{
        if(!adFormText.value){
            adFormText.value = adFormText.placeholder;
        }
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
    }
    
    // выпадающий список валют
    const dropButton = document.querySelector("#selected-currency");
    const dropList = document.querySelector("#currencies-hidden");
    
    const checkDropdown = (event)=>{
        if(dropButton.classList.contains("currencies-active")){
            dropButton.classList.remove("currencies-active");
            dropList.classList.remove("active");
            document.removeEventListener("click", checkDropdown);
        } else{
            dropButton.classList.add("currencies-active");
            dropList.classList.add("active");
            document.addEventListener("click", checkDropdown);
        }
        event.stopPropagation();
    }
    const toggleDropdown = (event)=>{
        checkDropdown(event); 
    }

    if( dropButton ){
        dropButton.addEventListener("click", toggleDropdown, false);
        linksToVk.forEach( link =>{
            link.addEventListener("click", function(e){
                let adId = [ e.target.dataset.id, 0 ];
                getPhone( adId, "link" );
            }, false);
        });
    }
});