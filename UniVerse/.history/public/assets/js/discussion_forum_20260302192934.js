
document.addEventListener
document.addEventListener("DOMContentLoaded",() => {
    const baseUrl = document.body.dataset.baseUrl?.replace(/\/$/, "") || "";
    const input = document.getElementById("forum_search");
    const rowContainer = document.querySelector(".discussion-table");

    if(!input || !rowContainer){
        return;
    }

    let timer = null;
    input.addEventListener("input" ,()=>{
        clearTimeout(timer);
        timer = setTimeout(async()=>{
            const q = input.value.trim();

            if(!q){
                window.location.href = `${baseUrl}/Discussion_Forum/index`;
                return;
            }
            const res = await fetch(`${baseUrl}/Discussion_Forum/search?q=${encodeURIComponent(q)}`);
            const json = await res.json();
            const threads = json.data || [];

            const existingRows = rowContainer.querySelectorAll(".discussion-row");
            existingRows.forEach(n=>n.remove());

            threads.forEach(t=>{
                const row = document.createElement("div");
                row.className = "discussion-row";
                row.innerHTML = `
                <div class="col-topic">
                    <div class="topic-title">
                    <a
                        href="${baseUrl}/Discussion_Forum/view_thread/${t.thread_id}">${escapeHtml(t.title)}</a>
                    </div>
                        <div class="topic-details">
                            posted by:<span class="author-name">${escapeHtml(t.author_name)}</span>
                            in <span class="category-link">${escapeHtml(t.category_name)}</span>
                        </div>
                </div>

                <div class="col-replies"><div class="stat-num">${t.replies ?? 0}</div></div>
                <div class="col-views"><div class="stat-num">${t.views ?? 0}</div></div>

                <div class="col-last-activity">
                    <div class="activity-details">
                        <div class="activity-author">by:${escapeHtml(t.last_author ?? "")}</div>
                        <div class="activity-time">${escapeHtml(t.last_edited ?? "")}</div>
                    </div>
                </div>`;

                rowContainer.appendChild(row);
            });
        },250);
    });

    function escapeHtml(s){
        return String(s ?? "")
        .replaceAll("&","&amp;")
        .replaceAll("<","&lt;")
        .replaceAll(">","&gt;")
        .replaceAll('"',"&quot;")
        .replaceAll("'","&#039;");
    }
});

