<!DOCTYPE html>
  <head>
    
    <style>
      body.company {
        background: #fff;
        color: #222;
        min-height: 100vh;
        margin: 0;
        font-family: "Segoe UI", Arial, sans-serif;
      }
      .company-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 40px;
        background: #fff;
        border-bottom: 1px solid #eee;
      }
      .company-logo {
        font-family: "Brush Script MT", cursive;
        font-size: 1.7rem;
        font-weight: bold;
      }
      .company-nav {
        display: flex;
        align-items: center;
        gap: 28px;
      }
      .company-nav a {
        color: #222;
        text-decoration: none;
        font-size: 0.98rem;
        margin: 0 8px;
        transition: color 0.2s;
      }
      .company-nav a:hover {
        color: #0077b6;
      }
      .company-dropdown {
        position: relative;
        display: inline-block;
      }
      .company-dropbtn {
        cursor: pointer;
      }
      .company-dropdown-content {
        display: none;
        position: absolute;
        background: #fff;
        min-width: 120px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        z-index: 1;
      }
      .company-dropdown-content a {
        color: #222;
        padding: 10px 16px;
        text-decoration: none;
        display: block;
        font-size: 0.95rem;
      }
      .company-dropdown-content a:hover {
        background: #f1f1f1;
      }
      .company-dropdown:hover .company-dropdown-content {
        display: block;
      }
      /* Main Content */
      .company-main {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 60vh;
        padding: 60px 0 0 0;
        gap: 40px;
      }
      .company-content {
        max-width: 500px;
        margin-left: 40px;
      }
      .company-content .company-section-title {
        color: #bbb;
        font-size: 1.3rem;
        font-weight: 500;
        margin-bottom: 10px;
      }
      .company-content h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 12px;
        margin-top: 0;
      }
      .company-content p {
        font-size: 1rem;
        color: #222;
        margin-bottom: 28px;
        margin-top: 0;
      }
      .company-content .try-btn {
        background: #fff;
        color: #222;
        border: 1.5px solid #222;
        padding: 8px 22px;
        font-size: 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s, color 0.2s;
      }
      .company-content .try-btn:hover {
        background: #222;
        color: #fff;
      }
      .company-img {
        width: 380px;
        height: 340px;
        background: #dedede;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 40px;
      }
      .company-img img {
        width: 90px;
        opacity: 0.5;
      }
      /* Footer */
      .company-footer {
        background: #fafafa;
        border-top: 1px solid #eee;
        padding: 30px 20px 10px 20px;
        font-size: 0.97rem;
        color: #444;
        margin-top: 60px;
      }
      .company-footer-top {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 18px;
      }
      .company-footer-col {
        min-width: 120px;
        margin-bottom: 10px;
      }
      .company-footer-col h4 {
        color: #ccc;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 10px;
      }
      .company-footer-logo {
        font-family: "Brush Script MT", cursive;
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 10px;
      }
      .company-footer-links {
        display: flex;
        flex-direction: column;
        gap: 6px;
      }
      .company-footer-links a {
        color: #444;
        text-decoration: none;
        font-size: 0.97rem;
        transition: color 0.2s;
      }
      .company-footer-links a:hover {
        color: #0077b6;
      }
      .company-footer-social {
        display: flex;
        gap: 12px;
        margin-top: 8px;
      }
      .company-footer-social a img {
        filter: grayscale(1) brightness(0.5);
        transition: filter 0.2s;
      }
      .company-footer-social a img:hover {
        filter: none;
      }
      .company-footer-bottom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid #eee;
        padding-top: 10px;
        font-size: 0.93rem;
        color: #888;
      }
      .company-footer-bottom a {
        color: #888;
        margin-left: 12px;
        text-decoration: none;
        font-size: 0.93rem;
      }
      .company-footer-bottom a:hover {
        text-decoration: underline;
      }
      @media (max-width: 1000px) {
        .company-main {
          flex-direction: column;
          align-items: center;
          gap: 30px;
        }
        .company-content {
          margin-left: 0;
          text-align: center;
        }
        .company-img {
          margin-right: 0;
        }
      }
      @media (max-width: 600px) {
        .company-header {
          flex-direction: column;
          align-items: flex-start;
          gap: 10px;
          padding-left: 10px;
          padding-right: 10px;
        }
        .company-main {
          padding: 30px 0 0 0;
        }
        .company-img {
          width: 90vw;
          height: 180px;
        }
        .company-footer-top {
          flex-direction: column;
          gap: 18px;
        }
      }
    </style>
  </head>
  <body class="company">
    <!-- Header -->
    <header class="company-header">
      <div class="company-logo">Logo</div>
      <nav class="company-nav">
        <a href="#">Home</a>
        <a href="#">My Profile</a>
        <div class="company-dropdown">
          <a href="#" class="company-dropbtn"
            >Job Postings <span>&#9660;</span></a
          >
          <div class="company-dropdown-content">
            <a href="#">Post a Job</a>
            <a href="#">Manage Jobs</a>
          </div>
        </div>
      </nav>
    </header>
    <!-- Main Content -->
    <main class="company-main">
      <section class="company-content">
        <div class="company-section-title">Content</div>
        <h1>Post Jobs & Internships Easily</h1>
        <p>
          Create and manage job or internship listings effortlessly. Reach
          thousands of qualified students across universities with just a few
          clicks.
        </p>
        <button class="try-btn">
          <span style="font-size: 1.1em">&#128640;</span> Try Now
        </button>
      </section>
      <div class="company-img">
        <img
          src="https://img.icons8.com/ios-filled/100/000000/image.png"
          alt="Placeholder"
        />
      </div>
    </main>
    
    
    <script>
      // Dropdown menu for Job Postings
      document.querySelectorAll(".company-dropbtn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
          e.preventDefault();
          const dropdown = this.nextElementSibling;
          dropdown.style.display =
            dropdown.style.display === "block" ? "none" : "block";
        });
      });
      document.addEventListener("click", function (e) {
        if (!e.target.closest(".company-dropdown")) {
          document
            .querySelectorAll(".company-dropdown-content")
            .forEach((d) => (d.style.display = "none"));
        }
      });
    </script>
  </body>
