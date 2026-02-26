-- ============================================================================
-- ARTICLE_VIEWS TABLE
-- Tracks unique views per user (logged-in) to prevent duplicate view counts.
-- Guest views are deduplicated via PHP session on the application side.
-- ============================================================================

CREATE TABLE IF NOT EXISTS article_views (
    view_id    INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id    INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- One row per user per article
    UNIQUE KEY unique_view (article_id, user_id),

    -- Foreign Keys
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)    REFERENCES users(user_id)    ON DELETE CASCADE,

    -- Indexes
    INDEX idx_article_id (article_id),
    INDEX idx_user_id    (user_id)
);
