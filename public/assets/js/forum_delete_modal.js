document.addEventListener("DOMContentLoaded",()=> {
    const baseUrl = (document.body.dataset.baseUrl || "").replace(/\/$/, "");
    const modal = document.getElementById("deleteModal");
    const cancelBtn = document.getElementById("cancelDelete");
    const confirmBtn = document.getElementById("confirmDelete");

    if(!modal || !cancelBtn || !confirmBtn){
        return;
    }

    function openModal(threadId){
        confirmBtn.href = `${baseUrl}/Discussion_Forum/delete_post/${encodeURIComponent(threadId)}`;
        modal.classList.remove("hidden");
        modal.setAttribute("aria-hidden", "false");
    }

    function closeModal(){
        modal.classList.add("hidden");
        modal.setAttribute("aria-hidden", "true");
        confirmBtn.href = "#";
    }

    document.addEventListener("click", (e) => {
        const btn = e.target.closest(".js-delete-thread");
        if(!btn)return;

        const threadId = btn.dataset.threadId;
        if(!threadId)return;

        openModal(threadId);
    });

    cancelBtn.addEventListener("click", closeModal);

    modal.addEventListener("click",(e) =>{
        if(e.target === modal){
            closeModal();
        }
    });

    document.addEventListener("keydown" ,(e)=>{
        if(e.key === "Escape"){
            closeModal();
        }
    });
});