<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        
        <!-- Auth Navigation Actions -->
        <div class="auth-nav-actions">
            <button onclick="history.back()" class="back-btn" title="Go back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </button>
        </div>
        
        <!-- <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link">For Students <span class="dropdown-arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">🎓</span>
                            <div>
                                <div class="dropdown-title">Degree Finder</div>
                                <div class="dropdown-desc">Find your perfect degree match</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">💼</span>
                            <div>
                                <div class="dropdown-title">Jobs & Internships</div>
                                <div class="dropdown-desc">Browse opportunities</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">👥</span>
                            <div>
                                <div class="dropdown-title">Mentorship</div>
                                <div class="dropdown-desc">Connect with mentors</div>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link">Resources <span class="dropdown-arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">📚</span>
                            <div>
                                <div class="dropdown-title">Career Articles</div>
                                <div class="dropdown-desc">Expert insights and tips</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">💬</span>
                            <div>
                                <div class="dropdown-title">Discussion Forums</div>
                                <div class="dropdown-desc">Connect with peers</div>
                            </div>
                        </a>
                    </div>
                </li>      
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>
        </nav> -->
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
