let selectThreadId = null;
const BASEURL = document.body.dataset.baseUrl;

//Delete button click
let DeleteBtn = document.querySelectorAll('.btn-delete');
let cancelDelete = document.getElementById('cancelDelete');
let confirmDelete = document.getElementById('confirmDelete');
let deleteModal = document.getElementById('deleteModal');

DeleteBtn.forEach(button =>{
    button.addEventListener('click',
    ()=>{
        selectThreadId = button.dataset.threadId;
        openDeleteModal();
    }
    );
});

cancelDelete.addEventListener('click', closeDeleteModal);

confirmDelete.addEventListener('click', ()=>{
    if(selectThreadId){
        window.location.href = BASEURL + '/Discussion_Forum/delete_post/'+selectThreadId;
    }
});

function openDeleteModal(){
    deleteModal.classList.remove('hidden');
}

function closeDeleteModal(){
    deleteModal.classList.add('hidden');
    selectThreadId = null;
}

document.addEventListener("DOMContentLoaded",() => {
    const baseUrl = document.
})

