(function () {
  const ROOT = window.__APP_ROOT__ || '';
  const fab   = document.getElementById('fabNew');
  const modal = document.getElementById('newThreadModal');
  if (!fab || !modal) return;

  const closeXs = modal.querySelectorAll('.modal-close');
  const form    = document.getElementById('newThreadForm');
  const errors  = document.getElementById('formErrors');

  function openModal () {
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeModal () {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  fab.addEventListener('click', openModal);
  closeXs.forEach(btn => btn.addEventListener('click', closeModal));
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal(); });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errors.style.display = 'none';
    errors.textContent = '';

    try {
      const res = await fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form) });
      const json = await res.json();
      if (!res.ok || json.errors) {
        const arr = json.errors || [json.error || 'Unable to create the thread'];
        errors.textContent = arr.join(' • ');
        errors.style.display = 'block';
        return;
      }
      window.location.href = json.url || (ROOT + '/forum/thread/' + json.id);
    } catch (err) {
      errors.textContent = 'Network error. Please try again.';
      errors.style.display = 'block';
    }
  });
})();
