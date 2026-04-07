document.addEventListener("DOMContentLoaded", () => {
  document.addEventListener("click", (e) => {
    const editBtn = e.target.closest(".js-edit-reply");
    const cancelBtn = e.target.closest(".js-cancel-edit");

    // Cancel editing
    if (cancelBtn) {
      const form = cancelBtn.closest(".js-reply-edit-form");
      const reply = cancelBtn.closest(".reply");
      if (!form || !reply) return;

      form.classList.add("hidden");

      const textEl = reply.querySelector(".js-reply-text");
      if (textEl) textEl.classList.remove("hidden");

      return;
    }

    // Start editing
    if (editBtn) {
      const reply = editBtn.closest(".reply");
      if (!reply) return;

      const textEl = reply.querySelector(".js-reply-text");
      const form = reply.querySelector(".js-reply-edit-form");
      const textarea = reply.querySelector(".js-edit-textarea");

      if (!textEl || !form || !textarea) return;

      // Load original raw content into textarea
      textarea.value = textEl.dataset.raw || "";

      // Toggle UI
      textEl.classList.add("hidden");
      form.classList.remove("hidden");

      // Focus
      textarea.focus();
      textarea.setSelectionRange(textarea.value.length, textarea.value.length);
      textarea.style.height = "auto";
      textarea.style.height = textarea.scrollHeight + "px";
    }
  });
});
