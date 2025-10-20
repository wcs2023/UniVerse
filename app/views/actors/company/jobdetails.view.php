<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Job Details - UniVerse</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
  <style>
    .job-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin: 1rem 0; }
    .meta-item { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 1rem; }
    .meta-label { font-size: .8rem; color: #666; text-transform: uppercase; letter-spacing: .05em; }
    .meta-value { font-weight: 600; color: #1f2937; margin-top: .25rem; }
    .section-title { margin: 1.25rem 0 .5rem; color: #111827; font-size: 1.1rem; }
    .section-box { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 1rem; white-space: pre-wrap; }
    .actions { display:flex; gap:.75rem; margin-top: 1.5rem; }
  </style>
</head>
<body>
  <header class="company-header">
    <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
    <nav class="company-nav">
      <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
      <a href="<?= BASE_URL ?>/company/managejobs" class="active">Manage Jobs</a>
      <a href="<?= BASE_URL ?>/company/postjobs">Post Jobs</a>
      <a href="<?= BASE_URL ?>/company/applications">View Applications</a>
    </nav>
    <div class="user-profile-dropdown">
      <div class="profile-trigger">
        <div class="profile-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
        </div>
        <span class="profile-name"><?= $user->firstname ?? 'User' ?></span>
        <div class="dropdown-arrow">▼</div>
      </div>
      <div class="dropdown-menu">
        <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item">Update Profile</a>
        <a href="<?= BASE_URL ?>/login/logout" class="dropdown-item logout">Logout</a>
      </div>
    </div>
  </header>

  <main class="main-content">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title"><?= htmlspecialchars($data['job']->job_title) ?></h2>
        <p class="card-subtitle">Posted on <?= date('M d, Y', strtotime($data['job']->created_at)) ?></p>
      </div>

      <div class="job-meta">
        <div class="meta-item">
          <div class="meta-label">Job Type</div>
          <div class="meta-value"><?= ucfirst($data['job']->job_type) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Location</div>
          <div class="meta-value"><?= htmlspecialchars($data['job']->location) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Deadline</div>
          <div class="meta-value"><?= date('M d, Y', strtotime($data['job']->deadline)) ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Salary</div>
          <div class="meta-value"><?= $data['job']->salary ? htmlspecialchars($data['job']->salary) : 'Not specified' ?></div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Status</div>
          <?php 
            $badgeClass = 'badge-secondary';
            if ($data['job']->status === 'active') $badgeClass = 'badge-success';
            elseif ($data['job']->status === 'closed') $badgeClass = 'badge-warning';
          ?>
          <div class="meta-value"><span class="badge <?= $badgeClass ?>"><?= ucfirst($data['job']->status) ?></span></div>
        </div>
      </div>

      <h3 class="section-title">Description</h3>
      <div class="section-box"><?= nl2br(htmlspecialchars($data['job']->description)) ?></div>

      <h3 class="section-title">Requirements</h3>
      <div class="section-box"><?= nl2br(htmlspecialchars($data['job']->requirements)) ?></div>

      <?php if (!empty($data['job']->responsibilities)): ?>
        <h3 class="section-title">Responsibilities</h3>
        <div class="section-box"><?= nl2br(htmlspecialchars($data['job']->responsibilities)) ?></div>
      <?php endif; ?>

      <div class="actions">
        <a href="<?= BASE_URL ?>/company/managejobs" class="btn btn-secondary">Back to Jobs</a>
        <a href="<?= BASE_URL ?>/company/postjobs?id=<?= $data['job']->id ?>" class="btn btn-primary">Edit Job</a>
        <button class="btn btn-danger" onclick="deleteJob(<?= $data['job']->id ?>, '<?= htmlspecialchars($data['job']->job_title) ?>')">Delete Job</button>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const trigger = document.querySelector('.profile-trigger');
      const menu = document.querySelector('.dropdown-menu');
      if (trigger && menu) {
        trigger.addEventListener('click', function(e) { e.stopPropagation(); menu.classList.toggle('active'); });
        document.addEventListener('click', function() { menu.classList.remove('active'); });
        menu.addEventListener('click', function(e) { e.stopPropagation(); });
      }
    });
    function deleteJob(jobId, jobTitle) {
      if (confirm(`Are you sure you want to delete the job "${jobTitle}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/company/delete';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'job_id';
        input.value = jobId;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
</body>
</html>
