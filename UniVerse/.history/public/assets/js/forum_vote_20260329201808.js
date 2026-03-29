document.addEventListener("DOMContentLoaded", () => {
  const baseUrl = (document.body.dataset.baseUrl || "").replace(/\/$/, "");

  async function postVote(url, onSuccess) {
    const res = await fetch(url, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" }
    });

    const data = await res.json().catch(() => null);

    if (!res.ok || !data || data.ok !== true) {
      if (res.status === 401) {
        window.location.href = `${baseUrl}/Login/index`;
        return;
      }
      alert(data?.error || "Vote failed");
      return;
    }

    onSuccess(data);
  }

  // THREAD votes
  document.addEventListener("click", (e) => {
    const likeBtn = e.target.closest(".js-thread-like");
    const dislikeBtn = e.target.closest(".js-thread-dislike");
    if (!likeBtn && !dislikeBtn) return;

    const btn = likeBtn || dislikeBtn;
    const threadId = btn.dataset.threadId;
    if (!threadId) return;

    const likeCountEl = document.querySelector(".js-thread-like-count");

    if (likeBtn) {
      postVote(`${baseUrl}/Discussion_Forum/like_thread/${encodeURIComponent(threadId)}`, (data) => {
        if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
      });
    } else {
      postVote(`${baseUrl}/Discussion_Forum/dislike_thread/${encodeURIComponent(threadId)}`, (data) => {
        if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
      });
    }
  });

  // REPLY votes
  document.addEventListener("click", (e) => {
    const likeBtn = e.target.closest(".js-reply-like");
    const dislikeBtn = e.target.closest(".js-reply-dislike");
    if (!likeBtn && !dislikeBtn) return;

    const btn = likeBtn || dislikeBtn;
    const postId = btn.dataset.postId;
    if (!postId) return;

    const actions = btn.closest(".reply-actions");
    if (!actions) return;

    const likeCountEl = actions.querySelector(".js-reply-like-count");
    const dislikeCountEl = actions.querySelector(".js-reply-dislike-count");

    if (likeBtn) {
      postVote(`${baseUrl}/Discussion_Forum/like_reply/${encodeURIComponent(postId)}`, (data) => {
        if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
        if (dislikeCountEl) dislikeCountEl.textContent = data.dislikes ?? 0;
      });
    } else {
      postVote(`${baseUrl}/Discussion_Forum/dislike_reply/${encodeURIComponent(postId)}`, (data) => {
        if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
        if (dislikeCountEl) dislikeCountEl.textContent = data.dislikes ?? 0;
      });
    }
  });
});