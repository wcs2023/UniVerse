(function(){
    const modal = document.getElementById("degreeDetailModal");
    const mdUnicode = document.getElementById("md-unicode");
    const mdUniversity = document.getElementById("md-university");
    const mdCourse = document.getElementById("md-course");
    const mdCutoff = document.getElementById("md-cutoff");
    const mdDetails = document.getElementById("md-details");

    const BASE_URL  = (typeof window.__APP_ROOT__ ==='string') ? window.__APP_ROOT__ : '';

    function openModal(){
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden','false');
        const CloseBtn = modal.querySelector('.modal-close');
        if(CloseBtn) CloseBtn.focus();
        document.body.style.overflow = 'hidden';
    }

    function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden','true');
        document.body.style.overflow = '';
    }

    async function loadDetails(id){
        mdUnicode.textContent = '';
        mdUniversity.textContent = '';
        mdCourse.textContent ='';
        mdCutoff.textContent = '';
        mdDetails.textContent='Loading';
        
        try{
            const url = BASE_URL.replace(/\/$/, '')+ 'degree/details/' + encodeURIComponent(id);
            const res = await fetch (url,{headers:{'Accept':'application/json'}});
            if(!res.ok)throw new Error('HTTP '+ res.status);
            const data = await res.json();
            
            mdUnicode.textContent = data.unicode || '' ;
            mdUniversity.textContent = data.university || '';
            mdCourse.textContent     = data.course || '';
            mdCutoff.textContent     = data.cutoff || '';
            mdCourse.textContent = data.course || '';
            mdCutoff.textContent = data.cutoff || '';
            
            if(data.structured && typeof data.details === 'object' && data.details !== null){
                const parts = [];
                for(const [k,v] of Object.entries(data.details)){
                    const key = String(k).replace (/ _/g, '  ');
                    const value = Array.isArray(v) ? v.join(', ') : (v ?? '');
                    parts.push(`${key} : ${value}`);
                }
                mdDetails.textContent = parts.join('\n');
                
            }
            else{
                mdDetails.textContent = (data.details || '');
            }
            
        }
            catch(err){
                console.error(err);
                mdDetails.textContent ='Unable to load details';
            }
    }

    document.addEventListener('click',function(e){
        const btn = e.target.closest('.vier-details-btn');
        if(!btn)return;
        const id = btn.getAttribute('data-id');
        if (!id) return;
        openModal();
        loadDetails(id);
    });

    modal.addEventListener('click',function(e){
        if(e.target.classList.contains('modal-close') || e.target === modal){
            closeModal();
        }
    });

})();