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
                console.log(xhr.response);
                phoneBlock.classList.add("hidden_phone-visible");
                phoneBlock.innerText = xhr.response;
             }             
        }
    }
}
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
    linksToVk.forEach( link =>{
        link.addEventListener("click", function(e){
            let adId = [ e.target.dataset.id, 0 ];
            getPhone( adId, "link" );
        }, false);
    });
});