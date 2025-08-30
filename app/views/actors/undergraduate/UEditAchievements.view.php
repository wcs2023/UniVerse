<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Edit Achievements</title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="edit-achievements-container">
        <!-- Header -->
        <div class="page-header">
            <h1>Edit Achievements</h1>
            <nav class="breadcrumb">
                <a href="<?= BASE_URL ?>/umyprofile">My Profile</a>
                <span>></span>
                <span>Edit Achievements</span>
                <span>></span>
                <span>Settings</span>
            </nav>
        </div>

        <!-- Add New Achievement Modal -->
        <div class="add-achievement-modal">
            <div class="modal-header">
                <h2>Add New Achievement</h2>
                <p>Use the form below to add personal, educational, or extracurricular activity.</p>
            </div>

            <form class="achievement-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="achievement-title">Title</label>
                        <input type="text" id="achievement-title" name="title" placeholder="e.g. Capstone Research Project">
                    </div>
                    <div class="form-group">
                        <label for="achievement-type">Type</label>
                        <select id="achievement-type" name="type">
                            <option value="project">Project</option>
                            <option value="certificate">Certificate</option>
                            <option value="competition">Competition</option>
                            <option value="leadership">Leadership</option>
                            <option value="volunteer">Volunteer Work</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="achievement-description">Description</label>
                    <textarea id="achievement-description" name="description" rows="3" 
                              placeholder="Briefly describe your achievement"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="achievement-date">Date</label>
                        <input type="date" id="achievement-date" name="date">
                    </div>
                    <div class="form-group">
                        <label for="achievement-link">Link (Optional)</label>
                        <input type="url" id="achievement-link" name="link" 
                               placeholder="e.g. https://github.com/project-repo">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Achievement</button>
                </div>
            </form>
        </div>

        <!-- Your Achievements Section -->
        <div class="achievements-section">
            <h2>Your Achievements</h2>

            <div class="achievements-grid">
                <!-- Achievement Card 1 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Certificate</div>
                        <div class="achievement-date">2023-01-01</div>
                    </div>
                    <h3>Community Leadership Certificate</h3>
                    <p class="achievement-desc">Awarded for outstanding leadership and volunteer work in local community initiatives, including organizing several...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>

                <!-- Achievement Card 2 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Activity</div>
                        <div class="achievement-date">2023-05-15</div>
                    </div>
                    <h3>Robotics Club President</h3>
                    <p class="achievement-desc">Led the university robotics club, guiding the team to develop an autonomous robot that won the regional...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>

                <!-- Achievement Card 3 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Project</div>
                        <div class="achievement-date">2023-08-20</div>
                    </div>
                    <h3>Capstone Research Project</h3>
                    <p class="achievement-desc">Developed a novel machine learning model for predictive analytics in healthcare data, demonstrating enhanced...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>

                <!-- Achievement Card 4 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Activity</div>
                        <div class="achievement-date">2023-03-10</div>
                    </div>
                    <h3>Web Development Bootcamp</h3>
                    <p class="achievement-desc">Completed a 6-month intensive of technologies inc. contributing to the front-end development of their main client...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>

                <!-- Achievement Card 5 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Certificate</div>
                        <div class="achievement-date">2023-06-25</div>
                    </div>
                    <h3>Python Programming Certificate</h3>
                    <p class="achievement-desc">Successfully completed an advanced Python programming course with a focus on data structures and algorithms...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>

                <!-- Achievement Card 6 -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-type">Activity</div>
                        <div class="achievement-date">2023-04-30</div>
                    </div>
                    <h3>University Debate Team</h3>
                    <p class="achievement-desc">Member of the university debate team, participating in national competitions and achieving public speaking and critical...</p>
                    <div class="achievement-actions">
                        <a href="#" class="action-link">View Link</a>
                        <button class="action-btn edit-btn" title="Edit">
                            <i class="icon-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" title="Delete">
                            <i class="icon-delete"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bulk-actions">
                <button class="btn btn-outline">Cancel All Changes</button>
                <button class="btn btn-primary">Save All Changes</button>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="page-footer">
            <div class="footer-links">
                <a href="#" class="footer-link">Resources</a>
                <a href="#" class="footer-link">Legal</a>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="icon-instagram"></i></a>
                    <a href="#" class="social-link"><i class="icon-facebook"></i></a>
                    <a href="#" class="social-link"><i class="icon-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
</body>
</html>
