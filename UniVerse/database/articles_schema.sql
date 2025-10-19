-- Articles system database structure
-- Run this SQL in your MySQL database (my_db)

-- Users table (if not exists)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin', 'author') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Insert sample users
INSERT IGNORE INTO users (id, name, email, role) VALUES
(1, 'Dr. Sarah Johnson', 'sarah.johnson@universe.edu', 'author'),
(2, 'Prof. Michael Chen', 'michael.chen@universe.edu', 'author'),
(3, 'Emma Rodriguez', 'emma.rodriguez@universe.edu', 'author'),
(4, 'News Team', 'news@universe.edu', 'author'),
(5, 'Alumni Office', 'alumni@universe.edu', 'author'),
(6, 'Dr. James Wilson', 'james.wilson@universe.edu', 'author'),
(7, 'Arts Department', 'arts@universe.edu', 'author'),
(8, 'Dr. Lisa Green', 'lisa.green@universe.edu', 'author'),
(9, 'Dr. Amanda Foster', 'amanda.foster@universe.edu', 'author');

-- Insert sample articles
INSERT INTO articles (title, excerpt, content, category, author_id, image, status, views, likes, shares, created_at) VALUES
('Breakthrough in AI-Powered Drug Discovery', 
 'Researchers at our university have achieved a significant milestone in artificial intelligence applications for accelerating drug discovery processes.',
 '<p>Our research team has made groundbreaking progress in using artificial intelligence to accelerate the drug discovery process. This innovative approach combines machine learning algorithms with traditional pharmaceutical research methods.</p><p>The new AI system can analyze millions of molecular compounds in hours rather than years, potentially saving decades of research time and billions in development costs.</p><h3>Key Achievements</h3><ul><li>95% accuracy in compound analysis</li><li>50x faster than traditional methods</li><li>Potential for treating rare diseases</li></ul>',
 'Research', 1, '/assets/images/articles/ai-research.jpg', 'published', 156, 23, 12, '2025-08-28 10:00:00'),

('The Future of Online Learning: Hybrid Models',
 'An insightful look into hybrid learning models that combine traditional classroom teaching with digital tools and enhanced engagement for students.',
 '<p>The education landscape is rapidly evolving, with hybrid learning models becoming the new standard. Our study shows that combining in-person and online elements creates more effective learning outcomes.</p><p>Students report higher engagement levels and better retention rates when using hybrid approaches compared to purely online or traditional classroom settings.</p><h3>Benefits of Hybrid Learning</h3><ul><li>Flexible scheduling</li><li>Personalized learning paths</li><li>Better work-life balance</li><li>Access to global resources</li></ul>',
 'Education', 2, '/assets/images/articles/hybrid-learning.jpg', 'published', 234, 45, 18, '2025-08-26 14:30:00'),

('Navigating Campus Life: Tips for New Students',
 'Essential advice for incoming freshmen to make the most of their university experience, from academic success to campus involvement activities.',
 '<p>Starting university can be overwhelming, but with the right approach, it becomes an exciting journey of growth and discovery. Here are our top recommendations for new students.</p><h3>Academic Success Tips</h3><ul><li>Attend all lectures and tutorials</li><li>Form study groups</li><li>Use campus resources like libraries and tutoring centers</li><li>Meet with professors during office hours</li></ul><h3>Campus Involvement</h3><p>Join clubs and societies that align with your interests. This is one of the best ways to make friends and develop leadership skills.</p>',
 'Student Life', 3, '/assets/images/articles/campus-life.jpg', 'published', 189, 31, 15, '2025-08-25 09:15:00'),

('University Celebrates 150 Years of Excellence',
 'A milestone celebration reflecting on our institution\'s rich history, highlighting key contributions to research, education, and community development.',
 '<p>This year marks our university\'s 150th anniversary, a testament to our enduring commitment to academic excellence and innovation. From humble beginnings to becoming a world-renowned institution, our journey has been remarkable.</p><h3>Historical Milestones</h3><ul><li>1875: Founded with 50 students</li><li>1920: First research laboratory established</li><li>1965: International student exchange program launched</li><li>2000: Digital learning initiatives began</li><li>2025: 150th anniversary celebration</li></ul><p>Today, we serve over 30,000 students from 80 countries, continuing our mission to educate tomorrow\'s leaders.</p>',
 'Campus News', 4, '/assets/images/articles/anniversary.jpg', 'published', 312, 67, 28, '2025-08-23 16:00:00'),

('Alumni Spotlight: From Campus to CEO',
 'Meet our distinguished alumnus who transformed their university experience into entrepreneurial success, creating a multi-million-dollar innovation company.',
 '<p>John Smith, Class of 2010, exemplifies the entrepreneurial spirit our university fosters. Starting as a computer science student, he now leads TechInnovate, a company valued at over $100 million.</p><h3>The Journey</h3><p>"My time at university taught me not just technical skills, but how to think critically and solve complex problems," says Smith. "The diverse environment and supportive faculty gave me the confidence to pursue my dreams."</p><h3>Advice for Current Students</h3><ul><li>Take advantage of networking opportunities</li><li>Don\'t be afraid to fail and learn</li><li>Seek mentorship from faculty and alumni</li><li>Participate in startup competitions</li></ul>',
 'Alumni', 5, '/assets/images/articles/alumni-success.jpg', 'published', 198, 42, 19, '2025-08-22 11:30:00'),

('Innovations in Quantum Computing at University Lab',
 'Groundbreaking research in quantum computing technology brings our team to the forefront of computational science and practical applications.',
 '<p>Our quantum computing laboratory has achieved a major breakthrough in quantum error correction, bringing us closer to practical quantum computers that could revolutionize computing.</p><h3>Research Achievements</h3><ul><li>99.9% quantum gate fidelity</li><li>Extended quantum coherence time</li><li>Novel error correction protocols</li><li>Collaboration with leading tech companies</li></ul><p>This research has implications for cryptography, drug discovery, financial modeling, and artificial intelligence. We expect to see practical applications within the next 5-10 years.</p>',
 'Technology', 6, '/assets/images/articles/quantum-lab.jpg', 'published', 275, 58, 24, '2025-08-20 13:45:00'),

('Revitalizing Campus Art: A New Exhibition',
 'Our campus art gallery unveils a stunning new exhibition showcasing contemporary works by renowned national and local emerging artists.',
 '<p>The university art gallery is proud to present "Voices of Tomorrow," featuring works from 25 contemporary artists exploring themes of identity, technology, and social change.</p><h3>Featured Artists</h3><p>The exhibition includes pieces from both established artists and emerging talents, with several works created by our own art students and faculty members.</p><h3>Exhibition Details</h3><ul><li>Duration: September 1 - December 15, 2025</li><li>Location: University Art Gallery, Building A</li><li>Free admission for students and staff</li><li>Guided tours available on weekends</li></ul>',
 'Arts & Culture', 7, '/assets/images/articles/art-exhibition.jpg', 'published', 142, 29, 11, '2025-08-18 15:20:00'),

('Climate Change Adaptation Strategies',
 'New research initiatives focusing on sustainable solutions and climate adaptation reveal promising strategies for environmental challenges.',
 '<p>Our environmental science department has launched a comprehensive study on climate adaptation strategies for urban environments, with promising initial results.</p><h3>Research Focus Areas</h3><ul><li>Urban heat island mitigation</li><li>Sustainable water management</li><li>Green infrastructure development</li><li>Carbon capture technologies</li></ul><p>The research involves collaboration with city planners, environmental agencies, and international climate organizations. Early findings suggest that integrated approaches combining natural and technological solutions show the most promise.</p>',
 'Research', 8, '/assets/images/articles/climate-research.jpg', 'published', 203, 36, 14, '2025-08-16 10:30:00'),

('Personalized Learning Paths: A New Approach',
 'Educational innovation meets technology as our faculty develops personalized learning frameworks responsive to student learning styles.',
 '<p>Our education technology team has developed an innovative system that creates personalized learning paths for each student based on their learning style, pace, and academic goals.</p><h3>System Features</h3><ul><li>AI-powered learning analytics</li><li>Adaptive content delivery</li><li>Real-time progress tracking</li><li>Personalized feedback mechanisms</li></ul><p>Pilot studies show 40% improvement in student engagement and 25% better learning outcomes compared to traditional approaches. The system will be rolled out university-wide next semester.</p>',
 'Education', 9, '/assets/images/articles/personalized-learning.jpg', 'published', 167, 33, 16, '2025-08-14 12:00:00');
