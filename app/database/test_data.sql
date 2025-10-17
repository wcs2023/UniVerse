-- Insert sample test data for testing the application

-- Insert a test user (alumni)
INSERT INTO Users (email, password, role, full_name, is_active) VALUES
('test.alumni@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'Test Alumni', 1);
-- Password is: password

-- Get the last inserted user_id and insert alumni profile
SET @last_user_id = LAST_INSERT_ID();

INSERT INTO Alumni (user_id, first_name, last_name, title, company, bio, mentorship_status, linkedin_url, short_bio, available_for_mentorship) VALUES
(@last_user_id, 'Test', 'Alumni', 'Senior Developer', 'Tech Company', 'Experienced software engineer passionate about mentoring.', 'available', 'https://linkedin.com/in/test', 'I love helping students learn!', 1);

-- Insert sample skills
INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES
(LAST_INSERT_ID(), 'JavaScript'),
(LAST_INSERT_ID(), 'PHP'),
(LAST_INSERT_ID(), 'MySQL'),
(LAST_INSERT_ID(), 'React');

-- Insert sample experience
INSERT INTO AlumniExperience (alumni_id, title, company, start_date, end_date, description) VALUES
(LAST_INSERT_ID(), 'Senior Developer', 'Tech Company', '2020', 'Present', 'Leading development of web applications.');

-- Insert a test undergraduate user
INSERT INTO Users (email, password, role, full_name, is_active) VALUES
('test.student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'Test Student', 1);
-- Password is: password

SET @student_user_id = LAST_INSERT_ID();

INSERT INTO Undergraduates (user_id, first_name, last_name, program, year, major, interests) VALUES
(@student_user_id, 'Test', 'Student', 'Computer Science', 'Junior', 'Software Engineering', 'Web Development, AI');
