-- Corrected Articles table creation for UniVerse
-- Fixed to match existing users table structure

-- Articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    author_id INT,
    image VARCHAR(255),
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    shares INT DEFAULT 0,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Insert sample articles
INSERT INTO articles (title, excerpt, content, category, author_id, image, status, views, likes, shares, created_at) VALUES
('Breakthrough in AI-Powered Drug Discovery', 
 'Researchers at our university have achieved a significant milestone in artificial intelligence applications for accelerating drug discovery processes.',
 'Our university research team has made groundbreaking progress in using artificial intelligence to accelerate drug discovery. This innovative approach combines machine learning algorithms with molecular biology to identify potential therapeutic compounds in a fraction of the time traditionally required. The research, published in Nature Biotechnology, demonstrates how AI can predict molecular interactions and identify promising drug candidates with 95% accuracy. This breakthrough could revolutionize pharmaceutical development and bring life-saving medications to patients faster than ever before.',
 'Research', 1, 'placeholder-article.jpg', 'published', 125, 18, 7, '2024-01-15 10:30:00'),

('Student Innovation Showcase 2024', 
 'Annual showcase highlights exceptional creativity and problem-solving skills of our undergraduate students across various disciplines.',
 'The 2024 Student Innovation Showcase proved once again that our students are leading the way in creative problem-solving and technological advancement. This year featured over 200 projects from engineering, computer science, business, and liberal arts students. Highlights included a sustainable water purification system designed for rural communities, an AI-powered mental health support app, and a revolutionary biodegradable packaging solution. The event attracted industry leaders, potential investors, and fellow students, creating valuable networking opportunities and real-world application possibilities for these innovative projects.',
 'Student Life', 2, 'placeholder-article.jpg', 'published', 89, 12, 3, '2024-02-01 14:20:00'),

('Career Fair 2024: Connecting Students with Industry Leaders',
 'Record-breaking attendance at this year\'s career fair with over 150 companies participating and hundreds of job opportunities.',
 'The annual Career Fair 2024 exceeded all expectations with unprecedented participation from both students and industry partners. Over 150 companies, ranging from startups to Fortune 500 corporations, set up booths to connect with our talented student body. The event facilitated over 3,000 individual conversations, resulted in 500+ interview requests, and generated immediate job offers for dozens of students. Notable participants included tech giants like Google and Microsoft, financial institutions like Goldman Sachs, and innovative startups from various sectors. The fair also featured professional development workshops, resume review sessions, and networking events to maximize opportunities for career advancement.',
 'Career', 3, 'placeholder-article.jpg', 'published', 156, 24, 11, '2024-02-15 09:00:00');
