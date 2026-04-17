document.addEventListener("DOMContentLoaded", () => {
  // ✅ Get base URL correctly
  const baseUrl = (
    document.querySelector("main")?.dataset.baseUrl || ""
  ).replace(/\/$/, "");

  // ✅ Helper: POST vote
  async function postVote(url) {
    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });

      const data = await res.json().catch(() => null);

      if (!res.ok || !data || data.ok !== true) {
        if (res.status === 401) {
          window.location.href = `${baseUrl}/users/login`;
          return null;
        }
        alert(data?.error || "Vote failed");
        return null;
      }

      return data;
    } catch (err) {
      console.error("Vote error:", err);
      alert("Something went wrong");
      return null;
    }
  }

  // ✅ Event delegation for buttons
  document.addEventListener("click", async (e) => {
    const likeBtn = e.target.closest(".js-thread-like");
    const dislikeBtn = e.target.closest(".js-thread-dislike");

    if (!likeBtn && !dislikeBtn) return;

    const btn = likeBtn || dislikeBtn;
    const threadId = btn.dataset.threadId;
    if (!threadId) return;

    // ✅ Prevent spam clicking
    likeBtn?.setAttribute("disabled", "true");
    dislikeBtn?.setAttribute("disabled", "true");

    // ✅ Get count elements
    const container = btn.closest(".post-card");
    const likeCountEl = container.querySelector(".js-thread-like-count");
    const dislikeCountEl = container.querySelector(".js-thread-dislike-count");

    // ✅ Build URL
    const url = likeBtn
      ? `${baseUrl}/Discussion_Forum/like_thread/${encodeURIComponent(threadId)}`
      : `${baseUrl}/Discussion_Forum/dislike_thread/${encodeURIComponent(threadId)}`;

    const data = await postVote(url);

    if (data) {
      // ✅ Update counts
      if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
      if (dislikeCountEl) dislikeCountEl.textContent = data.dislikes ?? 0;

      // ✅ Reset button states
      const likeButton = container.querySelector(".js-thread-like");
      const dislikeButton = container.querySelector(".js-thread-dislike");

      likeButton?.classList.remove("active");
      dislikeButton?.classList.remove("disliked");

      // ✅ Apply correct state from backend
      if (data.user_vote === 1) {
        likeButton?.classList.add("active");
      } else if (data.user_vote === -1) {
        dislikeButton?.classList.add("disliked");
      }
    }

    // ✅ Re-enable buttons
    likeBtn?.removeAttribute("disabled");
    dislikeBtn?.removeAttribute("disabled");
  });

  // REPLY
  // REPLY
document.addEventListener("click", async (e) => {
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

  // ✅ define the buttons so you can toggle classes
  const likeButton = actions.querySelector(".js-reply-like");
  const dislikeButton = actions.querySelector(".js-reply-dislike");

  // ✅ Prevent spam clicking
  likeButton?.setAttribute("disabled", "true");
  dislikeButton?.setAttribute("disabled", "true");

  const url = likeBtn
    ? `${baseUrl}/Discussion_Forum/like_reply/${encodeURIComponent(postId)}`
    : `${baseUrl}/Discussion_Forum/dislike_reply/${encodeURIComponent(postId)}`;

  // ✅ await the async function
  const data = await postVote(url);

  if (data) {
    if (likeCountEl) likeCountEl.textContent = data.likes ?? 0;
    if (dislikeCountEl) dislikeCountEl.textContent = data.dislikes ?? 0;

    // reset + apply backend state
    likeButton?.classList.remove("active");
    dislikeButton?.classList.remove("disliked");

    if (Number(data.user_vote) === 1) {
      likeButton?.classList.add("active");
    } else if (Number(data.user_vote) === -1) {
      dislikeButton?.classList.add("disliked");
    }
  }

  likeButton?.removeAttribute("disabled");
  dislikeButton?.removeAttribute("disabled");
});
});
