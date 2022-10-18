window.addEventListener('DOMContentLoaded', () => {
    const headerNav = document.querySelector(".header-nav");
    const leftColumn = document.querySelector(".left_column");
    let eTop = headerNav.offsetTop;
    // console.log(headerNav);
    // addEventListener('scroll', (event) => {
    //     var top = (window.pageYOffset || document.scrollTop)  - (document.clientTop || 0);
        
    //     let eBottom = eTop + headerNav.clientHeight;
    //     console.log(top > 250)
    //     // console.log(headerNav.classList.contains('scrolled'))
    //     if( top > 250 ){
    //         if(!headerNav.classList.contains('scrolled') ) headerNav.classList.add("scrolled");
    //     } else{
    //         if(headerNav.classList.contains('scrolled') )headerNav.classList.remove("scrolled");
    //     }
    // });


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