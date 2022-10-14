window.addEventListener('DOMContentLoaded', () => {
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

    dropButton.addEventListener("click", toggleDropdown, false);
});