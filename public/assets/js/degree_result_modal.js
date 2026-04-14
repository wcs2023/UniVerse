function esc(s) {
  return String(s ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function renderList(items) {
  if (!Array.isArray(items) || items.length === 0) return "";
  return `<ul>${items.map(x => `<li>${esc(x)}</li>`).join("")}</ul>`;
}

function renderDetails(details) {
  if (!details || typeof details !== "object") return "<p>No details available.</p>";

  const blocks = [];

  // title
  if (details.title) blocks.push(`<h4>${esc(details.title)}</h4>`);

  // meta line
  const meta = [];
  if (details.course_code) meta.push(`Course Code: ${esc(details.course_code)}`);
  if (details.proposed_intake) meta.push(`Proposed Intake: ${esc(details.proposed_intake)}`);
  if (meta.length) blocks.push(`<p>${meta.join(" • ")}</p>`);

  // minimum eligibility
  if (Array.isArray(details.minimum_eligibility) && details.minimum_eligibility.length) {
    blocks.push(`<h5>Minimum eligibility requirements</h5>`);
    blocks.push(renderList(details.minimum_eligibility));
  }

  // subjects
  if (Array.isArray(details.subjects) && details.subjects.length) {
    blocks.push(`<h5>Subjects</h5>`);
    blocks.push(renderList(details.subjects));
  }

  // programme info
  const dl = [];
  if (details.degree_programme) dl.push(["Degree Programme", details.degree_programme]);
  if (details.available_university) dl.push(["Available University", details.available_university]);
  if (details.duration) dl.push(["Duration", details.duration]);

  if (dl.length) {
    blocks.push(`<h5>Programme</h5>`);
    blocks.push(
      `<dl class="details-list">` +
        dl.map(([k, v]) => `<dt>${esc(k)}</dt><dd>${esc(v)}</dd>`).join("") +
      `</dl>`
    );
  }

  // specializations
  if (Array.isArray(details.specializations) && details.specializations.length) {
    blocks.push(`<h5>Specializations</h5>`);
    blocks.push(renderList(details.specializations));
  }

  // notes
  if (Array.isArray(details.notes) && details.notes.length) {
    blocks.push(`<h5>Notes</h5>`);
    blocks.push(details.notes.map(n => `<p>${esc(n)}</p>`).join(""));
  }

  // fallback: show any extra keys
  const known = new Set([
    "title","course_code","proposed_intake",
    "minimum_eligibility","subjects",
    "degree_programme","available_university","duration",
    "specializations","notes"
  ]);

  const extras = Object.keys(details).filter(k => !known.has(k));
  if (extras.length) {
    blocks.push(`<h5>Other</h5>`);
    blocks.push(
      `<ul>` +
      extras.map(k => `<li><strong>${esc(k)}:</strong> ${esc(JSON.stringify(details[k]))}</li>`).join("") +
      `</ul>`
    );
  }

  return blocks.join("");
}

document.addEventListener("DOMContentLoaded", () => {
  const backdrop = document.getElementById("degreeDetailModal");
  if (!backdrop) return;

  const mdUnicode = document.getElementById("md-unicode");
  const mdUnicode2 = document.getElementById("md-unicode2");
  const mdUni = document.getElementById("md-university");
  const mdCourse = document.getElementById("md-course");
  const mdCutoff = document.getElementById("md-cutoff");
  const mdDetails = document.getElementById("md-details");

  const closeBtns = backdrop.querySelectorAll(".modal-close");

  function openModal() {
    backdrop.classList.add("is-open");
    backdrop.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    backdrop.classList.remove("is-open");
    backdrop.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".js-view-degree");
    if (!btn) return;

    mdUnicode.textContent = btn.dataset.unicode || "";
    mdUnicode2.textContent = btn.dataset.unicode2 || "";
    mdUni.textContent = btn.dataset.university || "";
    mdCourse.textContent = btn.dataset.degree || "";
    mdCutoff.textContent = btn.dataset.cutoff || "";

    let detailsObj = null;
    try {
      detailsObj = JSON.parse(btn.dataset.details || "{}");
    } catch {
      detailsObj = null;
    }

    mdDetails.innerHTML = renderDetails(detailsObj);
    openModal();
  });

  closeBtns.forEach((b) => b.addEventListener("click", closeModal));

  backdrop.addEventListener("click", (e) => {
    if (e.target === backdrop) closeModal();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && backdrop.classList.contains("is-open")) closeModal();
  });
});