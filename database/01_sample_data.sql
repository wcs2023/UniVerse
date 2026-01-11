-- ============================================================================
-- UniVerse Database - Sample Test Data
-- Created: October 18, 2025
-- Description: Sample data for testing the application
-- Note: All passwords are "password" (bcrypt hashed)
-- ============================================================================

USE my_db;

-- ============================================================================
-- SAMPLE USERS
-- ============================================================================

-- Alumni User
INSERT INTO Users (email, password, role, full_name, is_active) VALUES
('test.alumni@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'John Doe', 1);

SET @alumni_user_id = LAST_INSERT_ID();

-- Undergraduate User
INSERT INTO Users (email, password, role, full_name, is_active) VALUES
('test.student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'Jane Smith', 1);

SET @student_user_id = LAST_INSERT_ID();

-- Admin User
INSERT INTO Users (email, password, role, full_name, is_active) VALUES
('admin@universe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin User', 1);

-- ============================================================================
-- ALUMNI PROFILES
-- ============================================================================

INSERT INTO Alumni (user_id, first_name, last_name, title, company, bio, mentorship_status, linkedin_url, short_bio, available_for_mentorship) VALUES
(@alumni_user_id, 'John', 'Doe', 'Senior Software Engineer', 'Tech Corp', 'Experienced software engineer with 10+ years in the industry. Passionate about mentoring and helping students navigate their career path.', 'available', 'https://linkedin.com/in/johndoe', 'I love helping students transition into tech careers!', 1);

SET @alumni_id = LAST_INSERT_ID();

-- ============================================================================
-- ALUMNI EXPERIENCE
-- ============================================================================

INSERT INTO AlumniExperience (alumni_id, title, company, start_date, end_date, description) VALUES
(@alumni_id, 'Senior Software Engineer', 'Tech Corp', '2020', 'Present', 'Leading development of enterprise web applications using React and Node.js.'),
(@alumni_id, 'Software Developer', 'StartUp Inc', '2017', '2020', 'Full-stack development of customer-facing applications.'),
(@alumni_id, 'Junior Developer', 'Code Masters', '2015', '2017', 'Frontend development and bug fixing.');

-- ============================================================================
-- ALUMNI SKILLS
-- ============================================================================

INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES
(@alumni_id, 'JavaScript'),
(@alumni_id, 'React'),
(@alumni_id, 'Node.js'),
(@alumni_id, 'PHP'),
(@alumni_id, 'MySQL'),
(@alumni_id, 'Python'),
(@alumni_id, 'Git'),
(@alumni_id, 'Docker');

-- ============================================================================
-- UNDERGRADUATE PROFILES
-- ============================================================================

INSERT INTO Undergraduates (user_id, first_name, last_name, program, year, major, interests) VALUES
(@student_user_id, 'Jane', 'Smith', 'Bachelor of Computer Science', '3', 'Computer Science', 'Web Development, Artificial Intelligence, Machine Learning');

SET @undergraduate_id = LAST_INSERT_ID();

-- ============================================================================
-- SAMPLE ARTICLES
-- ============================================================================

INSERT INTO Articles (author_id, title, content, status, category, tags, views, likes, published_at) VALUES
(@alumni_user_id, 'Getting Started in Tech: A Complete Guide', 
'Starting a career in technology can be overwhelming, but with the right approach, you can navigate this journey successfully. Here are my top tips for landing your first tech job...

1. Build a Strong Foundation
Focus on learning the fundamentals of programming. Choose one language and master it before moving to others.

2. Create a Portfolio
Build real projects that solve real problems. This demonstrates your practical skills to potential employers.

3. Network Actively
Attend tech meetups, join online communities, and connect with professionals in your field.

4. Never Stop Learning
Technology evolves rapidly. Stay updated with the latest trends and continuously improve your skills.

Remember, everyone starts somewhere. Your first job is just the beginning of an exciting journey!', 
'published', 'Career Advice', 'career, technology, beginners, job hunting', 245, 42, NOW()),

(@alumni_user_id, 'The Importance of Mentorship in Tech', 
'Throughout my career, mentorship has been crucial to my growth. Here\'s why finding a mentor can accelerate your career...', 
'published', 'Professional Development', 'mentorship, career growth, networking', 189, 35, DATE_SUB(NOW(), INTERVAL 5 DAY)),

(@alumni_user_id, 'My Journey from Student to Senior Engineer', 
'This is a draft article about my career journey...', 
'draft', 'Success Stories', 'career, personal story', 0, 0, NULL);

-- ============================================================================
-- SAMPLE MENTORSHIP REQUEST
-- ============================================================================

INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) VALUES
(@undergraduate_id, @alumni_id, 'Career Guidance in Software Development', 
'Hi John! I\'m interested in pursuing a career in software development and would love to learn from your experience. I have questions about choosing the right technologies to focus on and how to prepare for technical interviews.', 
'I hope to gain insights into the software engineering industry and receive guidance on building a strong foundation for my career.', 
'pending', NOW());

-- ============================================================================
-- SAMPLE NOTIFICATION
-- ============================================================================

INSERT INTO Notifications (user_id, user_type, related_id, type, message, is_read, created_at) VALUES
(@alumni_user_id, 'alumni', 1, 'mentorship_request', 'You have received a new mentorship request from Jane Smith regarding "Career Guidance in Software Development".', 0, NOW()),
(@student_user_id, 'undergraduate', 1, 'mentorship_request', 'Your mentorship request to John Doe has been sent successfully. You will be notified when they respond.', 1, NOW());

-- ============================================================================
-- END OF SAMPLE DATA
-- ============================================================================

-- Show summary of inserted data
SELECT 'Database setup complete!' as Status;
SELECT COUNT(*) as Users FROM Users;
SELECT COUNT(*) as Alumni FROM Alumni;
SELECT COUNT(*) as Students FROM Undergraduates;
SELECT COUNT(*) as Articles FROM Articles;
SELECT COUNT(*) as 'Mentorship Requests' FROM Mentorships;
