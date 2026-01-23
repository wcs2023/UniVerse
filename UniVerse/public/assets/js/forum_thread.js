(function () {
  const ROOT = window.__APP_ROOT__ || '';

  // Like (vote) a reply
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.js-post-like');
    if (!btn) return;
    const postId = btn.getAttribute('data-id');
    try {
      const res = await fetch(`${ROOT}/forum/vote/${encodeURIComponent(postId)}`, { method: 'POST' });
      const json = await res.json();
      if (typeof json.score !== 'undefined') {
        const el = document.getElementById(`score-${postId}`);
        if (el) el.textContent = json.score;
      }
    } catch (err) {
      console.error(err);
    }
  });

  // Reply submit (AJAX)
  const form = document.getElementById('replyForm');
  const errBox = document.getElementById('replyError');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      errBox.style.display = 'none';
      errBox.textContent = '';
      try {
        const res = await fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form) });
        const json = await res.json();
        if (!res.ok || json.error) {
          errBox.textContent = json.error || 'Unable to post reply';
          errBox.style.display = 'block';
          return;
        }
        location.reload();
      } catch (err) {
        errBox.textContent = 'Network error';
        errBox.style.display = 'block';
      }
    });
  }
})();
