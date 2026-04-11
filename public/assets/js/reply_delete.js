(function (){
    const main = document.querySelector("main.container");

    if(!main){
        return;
    }

    const BASE_URL = main.dataset.baseUrl || "";
    const modal = document.getElementById("deleteModal");
    const form = document.getElementById("deleteForm");

    if(!modal || !form){
        return;
    }

    window.openDeleteModal = function(postId){
        form.action = `${BASE_URL}/Discussion_Forum/delete_reply/${postId}`;

        modal.classList.add("show");
        modal.setAttribute("aria-hidden", "false");

    };

    window.closeDeleteModal = function (){
        modal.classList.remove("show");
        modal.setAttribute("aria-hidden", "true");
    };

    const cancelBtn = modal.querySelector("[data-modal-cancel]");

    if(cancelBtn){
        cancelBtn.addEventListener("click", closeDeleteModal);
    };

    modal.addEventListener("click",(e)=>{
        if(e.target === modal){
            window.closeDeleteModal();
        };
    });

    document.addEventListener("keydown",(e)=>{
        if(e.key === "Escape"){
            window.closeDeleteModal();
        }
    });

})();