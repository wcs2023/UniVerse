<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Achievements</title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <img src="<?= BASE_URL ?>/assets/images/logo bw.PNG" alt="Profile Photo">
            </div>
            <div class="profile-info">
                <h1>John Doe</h1>
                <p class="degree-info">B.Sc. Computer Science (Class of 2026)</p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item">
                <!-- <i class="icon-person"></i> -->
                Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item active">
                <!-- <i class="icon-achievement"></i> -->
                Achievements
            </a>
            <a href="<?= BASE_URL ?>/usettings" class="nav-item">
                <!-- <i class="icon-settings"></i> -->
                Settings
            </a>
        </div>

        <!-- Achievements Section -->
        <div class="achievements-container">
            <div class="section-header">
                <h2>Your Achievements</h2>
                <button class="add-achievement-btn">+ Add Achievement</button>
            </div>

            <div class="achievements-grid">
                <!-- Community Leadership Certificate -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">Community Leadership Certificate</div>
                        <span class="achievement-tag certificate">Certificate</span>
                    </div>
                    <div class="achievement-date">2023-12-15</div>
                    <div class="achievement-description">
                        Awarded for outstanding leadership and volunteer work in local community 
                        initiatives, involving organizing several...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>

                <!-- Robotics Club President -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">Robotics Club President</div>
                        <span class="achievement-tag activity">Activity</span>
                    </div>
                    <div class="achievement-date">2023-09-01</div>
                    <div class="achievement-description">
                        Led the university robotics club, guiding the team to develop an autonomous 
                        navigation robot that won the regional...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>

                <!-- Capstone Research Project -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">Capstone Research Project</div>
                        <span class="achievement-tag project">Project</span>
                    </div>
                    <div class="achievement-date">2024-05-20</div>
                    <div class="achievement-description">
                        Developed a novel machine learning model for predictive analytics in 
                        renewable energy consumption, achieving...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>

                <!-- Web Development Internship -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">Web Development Internship</div>
                        <span class="achievement-tag activity">Activity</span>
                    </div>
                    <div class="achievement-date">2023-08-30</div>
                    <div class="achievement-description">
                        Completed a 3-month internship at TechSolutions Inc., contributing to the 
                        front-end development of their main client...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>

                <!-- Python Programming Course -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">Python Programming Course</div>
                        <span class="achievement-tag certificate">Certificate</span>
                    </div>
                    <div class="achievement-date">2023-05-20</div>
                    <div class="achievement-description">
                        Successfully completed an advanced Python programming course with a focus 
                        on data structures and algorithms...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>

                <!-- University Debate Team -->
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title">University Debate Team</div>
                        <span class="achievement-tag activity">Activity</span>
                    </div>
                    <div class="achievement-date">2022-11-01</div>
                    <div class="achievement-description">
                        Member of the university debate team, participating in national competitions and 
                        enhancing public speaking and critical...
                    </div>
                    <div class="achievement-actions">
                        <a href="#" class="view-link">🔗 View Link</a>
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    </div>
                    <div class="achievement-card academic-award">
                        <div class="achievement-icon">🥇</div>
                        <div class="achievement-content">
                            <h3>Excellence in Computer Science</h3>
                            <p class="achievement-desc">Top performer in Computer Science department</p>
                            <div class="achievement-meta">
                                <span class="achievement-date">Fall 2023</span>
                                <span class="achievement-institution">Faculty of Science</span>
                            </div>
                        </div>
                        <div class="achievement-actions">
                            <button class="view-btn">View</button>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </section>

    
            <!-- Certifications Section -->
            <section class="achievement-section">
                <div class="section-header">
                    <h2>Certifications</h2>
                    <span class="achievement-count">2 Certifications</span>
                </div>
                
                <div class="achievements-grid">
                    <div class="achievement-card certification">
                        <div class="achievement-icon">📜</div>
                        <div class="achievement-content">
                            <h3>AWS Cloud Practitioner</h3>
                            <p class="achievement-desc">Amazon Web Services Cloud Practitioner Certification</p>
                            <div class="achievement-meta">
                                <span class="achievement-date">January 2024</span>
                                <span class="achievement-provider">Amazon Web Services</span>
                            </div>
                        </div>
                        <div class="achievement-actions">
                            <button class="view-btn">View</button>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>

                    <div class="achievement-card certification">
                        <div class="achievement-icon">🔒</div>
                        <div class="achievement-content">
                            <h3>Cybersecurity Fundamentals</h3>
                            <p class="achievement-desc">Introduction to Cybersecurity certificate from Cisco</p>
                            <div class="achievement-meta">
                                <span class="achievement-date">November 2023</span>
                                <span class="achievement-provider">Cisco Networking Academy</span>
                            </div>
                        </div>
                        <div class="achievement-actions">
                            <button class="view-btn">View</button>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Publications Section -->
            <!-- <section class="achievement-section">
                <div class="section-header">
                    <h2>Publications</h2>
                    <span class="achievement-count">1 Publication</span>
                </div>
                
                <div class="achievements-grid">
                    <div class="achievement-card publication">
                        <div class="achievement-icon">📚</div>
                        <div class="achievement-content">
                            <h3>Research Paper: ML in Finance</h3>
                            <p class="achievement-desc">Co-authored research paper on machine learning applications in financial markets</p>
                            <div class="achievement-meta">
                                <span class="achievement-date">February 2024</span>
                                <span class="achievement-journal">IEEE Student Journal</span>
                            </div>
                        </div>
                        <div class="achievement-actions">
                            <button class="view-btn">View</button>
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </section> -->
        </div>
    </div>

    <!-- Add Achievement Modal (Hidden by default) -->
    <!-- <div class="modal" id="add-achievement-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Achievement</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form class="achievement-form">
                <div class="form-group">
                    <label for="achievement-type">Achievement Type</label>
                    <select id="achievement-type" name="type">
                        <option value="academic">Academic Award</option>
                        <option value="competition">Coding Competition</option>
                        <option value="certification">Certification</option>
                        <option value="publication">Publication</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="achievement-title">Title</label>
                    <input type="text" id="achievement-title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="achievement-description">Description</label>
                    <textarea id="achievement-description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="achievement-date">Date</label>
                    <input type="date" id="achievement-date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="achievement-organization">Organization/Institution</label>
                    <input type="text" id="achievement-organization" name="organization">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Achievement</button>
                </div>
            </form>
        </div>
    </div> -->

    <?php include 'UFooter.view.php'; ?>
    
    <!-- <script src="js/main.js"></script>
    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script> -->
</body>
</html>
