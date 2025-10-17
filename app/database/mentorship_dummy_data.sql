-- ============================================================================-- Dummy data for testing the mentorship UI

-- UniVerse Mentorship System - Comprehensive Dummy Data-- Run this script to populate your database with test data

-- Created: October 16, 2025

-- Description: Realistic sample data for testing all mentorship features-- Clear existing data (optional - uncomment if needed)

-- ============================================================================-- DELETE FROM MentorshipTimeSlots;

-- DELETE FROM Mentorships;

-- Clear existing data (in correct order to handle foreign keys)-- DELETE FROM AlumniSkills;

SET FOREIGN_KEY_CHECKS = 0;-- DELETE FROM AlumniExperience;

TRUNCATE TABLE Messages;-- DELETE FROM Notifications;

TRUNCATE TABLE Notifications;-- DELETE FROM Alumni;

TRUNCATE TABLE Feedback;-- DELETE FROM Undergraduates;

TRUNCATE TABLE Mentorship_Sessions;-- DELETE FROM Users;

TRUNCATE TABLE Proposed_Slots;

TRUNCATE TABLE Mentorship_Requests;-- Sample users

TRUNCATE TABLE Mentee_Profiles;INSERT INTO Users (email, password, role) VALUES

TRUNCATE TABLE Mentor_Profiles;('test.alumni1@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'alumni'),

TRUNCATE TABLE Users;('test.alumni2@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'alumni'),

SET FOREIGN_KEY_CHECKS = 1;('test.alumni3@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'alumni'),

('test.student1@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'undergraduate'),

-- ============================================================================('test.student2@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'undergraduate'),

-- 1. INSERT USERS (Mix of Undergraduates, Alumni, and Admin)('test.student3@example.com', '$2y$10$aH9S5jpJ.s.5bxnDWLT9AODhyBnxkf.2G0U1F8OoaABuZgyojHVEa', 'undergraduate');

-- Password for all users: 'password123' (hashed)

-- ============================================================================-- Sample alumni

INSERT INTO Users (first_name, last_name, email, password, role, profile_picture_url, account_status, last_login) VALUESINSERT INTO Alumni (user_id, first_name, last_name, title, company, bio, mentorship_status) VALUES

-- Undergraduates (Mentees)(1, 'Alex', 'Johnson', 'Senior Software Engineer', 'Tech Innovations', 'Experienced software engineer with 10+ years in the field. Specialized in full-stack development and cloud architecture.', 'available'),

('Sarah', 'Johnson', 'sarah.johnson@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=1', 'active', '2025-10-15 09:30:00'),(2, 'Samantha', 'Williams', 'Product Manager', 'Global Solutions Inc.', 'Product management professional with experience in tech startups and enterprise companies. Passionate about mentoring students.', 'available'),

('Michael', 'Chen', 'michael.chen@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=13', 'active', '2025-10-15 14:20:00'),(3, 'Michael', 'Chen', 'Data Scientist', 'Analytics Pro', 'Data science expert with background in machine learning and AI. Previously worked at top tech companies and eager to share knowledge.', 'available');

('Emily', 'Rodriguez', 'emily.rodriguez@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=5', 'active', '2025-10-14 18:45:00'),

('James', 'Williams', 'james.williams@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=12', 'active', '2025-10-15 11:00:00'),-- Sample alumni experience

('Olivia', 'Brown', 'olivia.brown@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=9', 'active', '2025-10-13 16:30:00'),INSERT INTO AlumniExperience (alumni_id, title, company, start_date, end_date, description) VALUES

('David', 'Martinez', 'david.martinez@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'undergraduate', 'https://i.pravatar.cc/150?img=15', 'active', '2025-10-15 08:15:00'),(1, 'Senior Software Engineer', 'Tech Innovations', 'Jan 2020', 'Present', 'Leading development teams and architecting cloud solutions'),

(1, 'Software Developer', 'CodeCraft', 'Mar 2015', 'Dec 2019', 'Full-stack development and DevOps implementation'),

-- Alumni (Mentors)(2, 'Product Manager', 'Global Solutions Inc.', 'Jun 2018', 'Present', 'Managing product roadmap and leading cross-functional teams'),

('Dr. Jennifer', 'Anderson', 'jennifer.anderson@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=10', 'active', '2025-10-15 10:00:00'),(2, 'Associate Product Manager', 'StartUp Hub', 'Aug 2015', 'May 2018', 'Helped launch 3 successful products and improved user metrics'),

('Robert', 'Taylor', 'robert.taylor@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=33', 'active', '2025-10-15 12:30:00'),(3, 'Data Scientist', 'Analytics Pro', 'Sep 2019', 'Present', 'Developing machine learning models for business intelligence'),

('Dr. Lisa', 'Thompson', 'lisa.thompson@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=47', 'active', '2025-10-14 15:45:00'),(3, 'Data Analyst', 'DataDriven LLC', 'Jul 2016', 'Aug 2019', 'Statistical analysis and data visualization for clients');

('William', 'Garcia', 'william.garcia@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=52', 'active', '2025-10-15 09:00:00'),

('Dr. Maria', 'Davis', 'maria.davis@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=44', 'active', '2025-10-15 13:15:00'),-- Sample alumni skills

('John', 'Wilson', 'john.wilson@alumni.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'alumni', 'https://i.pravatar.cc/150?img=60', 'active', '2025-10-14 11:20:00'),INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES

(1, 'JavaScript'),

-- Admin(1, 'React'),

('Admin', 'User', 'admin@universe.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'https://i.pravatar.cc/150?img=70', 'active', '2025-10-15 08:00:00');(1, 'Node.js'),

(1, 'AWS'),

-- ============================================================================(1, 'DevOps'),

-- 2. INSERT MENTOR PROFILES (For Alumni users)(2, 'Product Management'),

-- ============================================================================(2, 'Agile'),

INSERT INTO Mentor_Profiles (user_id, expertise_areas, professional_experience, current_company, current_position, years_of_experience, graduation_year, degree, major, is_available, average_rating, total_sessions, total_ratings, bio, linkedin_url, max_concurrent_mentees, response_time_hours) VALUES(2, 'UX Design'),

(7, 'Software Engineering, Web Development, System Design, Career Guidance', 'Led development teams at Google and Amazon. Built scalable systems serving millions of users. Passionate about mentoring early-career developers.', 'Google', 'Senior Software Engineer', 8, 2015, 'Bachelor of Science', 'Computer Science', TRUE, 4.80, 24, 20, 'I love helping students navigate their tech careers. With 8 years at top companies, I can provide insights on coding interviews, system design, and career growth.', 'https://linkedin.com/in/jennifer-anderson', 5, 24),(2, 'Market Research'),

(2, 'Strategic Planning'),

(8, 'Data Science, Machine Learning, AI, Python, Big Data', 'PhD in Machine Learning. Currently leading AI initiatives at Microsoft. Published 15+ research papers in top conferences.', 'Microsoft', 'Lead Data Scientist', 10, 2013, 'PhD', 'Computer Science', TRUE, 4.90, 18, 15, 'Former researcher turned industry practitioner. I can guide you through ML fundamentals, research opportunities, and transitioning from academia to industry.', 'https://linkedin.com/in/robert-taylor', 4, 48),(3, 'Python'),

(3, 'Machine Learning'),

(9, 'Product Management, UX Design, Agile, Startup Experience', 'Built products from 0 to 1. Experience at both startups and Fortune 500 companies. Led product teams of 20+ people.', 'Meta', 'Senior Product Manager', 12, 2011, 'Master of Business Administration', 'Business Administration', TRUE, 4.70, 30, 25, 'I help students understand the product management role and how to break into PM. Also great for discussing startup ideas!', 'https://linkedin.com/in/lisa-thompson', 6, 24),(3, 'Data Analysis'),

(3, 'SQL'),

(10, 'Cybersecurity, Network Security, Ethical Hacking, Cloud Security', 'CISSP certified. Led security teams at financial institutions. Expert in cloud security and penetration testing.', 'JPMorgan Chase', 'Chief Security Officer', 15, 2008, 'Master of Science', 'Cybersecurity', TRUE, 4.60, 12, 10, 'Security is critical in today\'s digital world. I can help you understand cybersecurity careers, certifications, and hands-on skills needed.', 'https://linkedin.com/in/william-garcia', 3, 72),(3, 'TensorFlow');



(11, 'Mobile Development, iOS, Android, Flutter, React Native', 'Built mobile apps with 10M+ downloads. Specialized in cross-platform development. Published author on mobile dev best practices.', 'Apple', 'Senior iOS Developer', 9, 2014, 'Bachelor of Science', 'Software Engineering', TRUE, 4.90, 22, 18, 'Mobile-first is the future! Whether you want to learn iOS, Android, or cross-platform development, I can help you build amazing apps.', 'https://linkedin.com/in/maria-davis', 5, 36),-- Sample undergraduates

INSERT INTO Undergraduates (user_id, first_name, last_name, program, year, major, interests) VALUES

(12, 'DevOps, Cloud Architecture, AWS, Kubernetes, CI/CD', 'AWS Certified Solutions Architect. Built and maintained infrastructure for companies processing billions of transactions.', 'Netflix', 'Principal DevOps Engineer', 11, 2012, 'Bachelor of Engineering', 'Computer Engineering', FALSE, 4.50, 8, 8, 'DevOps is more than just tools - it\'s a culture. I can teach you about cloud platforms, automation, and building reliable systems.', 'https://linkedin.com/in/john-wilson', 3, 48);(4, 'Emily', 'Parker', 'Bachelor of Science', 'Junior', 'Computer Science', 'Software development, web technologies, AI'),

(5, 'David', 'Lee', 'Bachelor of Business', 'Senior', 'Business Administration', 'Marketing, entrepreneurship, project management'),

-- ============================================================================(6, 'Jasmine', 'Rodriguez', 'Bachelor of Science', 'Sophomore', 'Data Science', 'Machine learning, statistics, data visualization');

-- 3. INSERT MENTEE PROFILES (For Undergraduate users)

-- ============================================================================-- Sample mentorship requests with various statuses

INSERT INTO Mentee_Profiles (user_id, current_year, major, interests, career_goals, gpa, university, expected_graduation, resume_url) VALUES-- 1. Pending requests

(1, 3, 'Computer Science', 'Web Development, Full Stack Development, Open Source', 'I want to become a full-stack software engineer at a major tech company. Particularly interested in working on scalable web applications.', 3.75, 'State University', 2026, 'https://resume.example.com/sarah-johnson'),INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) VALUES

(1, 1, 'Web Development Career Path', 'Hi Alex, I admire your experience in web development and would love to get some advice on career paths in this field.', 'I hope to understand the key skills I should focus on and how to best prepare for internships.', 'pending', DATE_SUB(NOW(), INTERVAL 2 DAY)),

(2, 2, 'Data Science', 'Machine Learning, Artificial Intelligence, Data Analytics', 'Aiming to work as a Machine Learning Engineer. Interested in applying AI to solve real-world problems in healthcare or finance.', 3.92, 'State University', 2027, 'https://resume.example.com/michael-chen'),(2, 3, 'Machine Learning Projects', 'I''m very interested in machine learning and would appreciate guidance on starting meaningful projects in this area.', 'I hope to get insights on what projects would be most valuable for my portfolio and future career.', 'pending', DATE_SUB(NOW(), INTERVAL 1 DAY));



(3, 4, 'Business Administration', 'Product Management, Marketing, Entrepreneurship', 'Want to transition into product management after graduation. Dream of launching my own startup one day.', 3.60, 'State University', 2026, 'https://resume.example.com/emily-rodriguez'),-- 2. Awaiting student confirmation (alumni has offered time slots)

INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) VALUES

(4, 3, 'Cybersecurity', 'Network Security, Ethical Hacking, Cryptography', 'Passionate about cybersecurity. Want to work as a security analyst and eventually become a penetration tester.', 3.85, 'State University', 2026, 'https://resume.example.com/james-williams'),(1, 2, 'Product Management Insights', 'I''m curious about transitioning into product management from my technical background.', 'I would like to understand the day-to-day responsibilities and skills needed to succeed.', 'awaiting_student_confirmation', DATE_SUB(NOW(), INTERVAL 5 DAY)),

(3, 2, 'Business Strategy for Tech Products', 'I would love to learn more about how product strategies are developed at successful companies.', 'I hope to gain insights into market research and product roadmapping processes.', 'awaiting_student_confirmation', DATE_SUB(NOW(), INTERVAL 4 DAY));

(5, 2, 'Software Engineering', 'Mobile Development, UI/UX Design, App Development', 'Love creating mobile apps. Want to become an iOS developer and work on apps that impact millions of users.', 3.70, 'State University', 2027, 'https://resume.example.com/olivia-brown'),

-- 3. Scheduled (upcoming sessions)

(6, 1, 'Computer Engineering', 'Cloud Computing, DevOps, System Administration', 'Interested in cloud technologies and DevOps practices. Want to work as a Cloud Engineer at a major cloud provider.', 3.55, 'State University', 2028, 'https://resume.example.com/david-martinez');INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date, scheduled_date, duration) VALUES

(2, 1, 'Frontend Development Best Practices', 'I would love to discuss modern frontend development practices and frameworks.', 'I hope to learn about industry-standard approaches and tools.', 'scheduled', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY), 60),

-- ============================================================================(3, 3, 'Data Science Career Opportunities', 'I would appreciate guidance on navigating career options in data science.', 'I hope to understand different specializations and required skills.', 'scheduled', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY), 45),

-- 4. INSERT MENTORSHIP REQUESTS(1, 3, 'AI Project Collaboration', 'I have a project idea in AI and would love your feedback and guidance.', 'I hope to refine my approach and implementation strategy.', 'scheduled', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY), 90);

-- ============================================================================

INSERT INTO Mentorship_Requests (mentee_id, mentor_id, request_date, status, mentee_message, topic, expectations, accepted_at, scheduled_at, completed_at) VALUES-- 4. Completed sessions

-- Request 1: Completed mentorshipINSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date, scheduled_date, duration, feedback) VALUES

(1, 1, '2025-09-15 10:00:00', 'completed', 'Hi Dr. Anderson! I\'m really interested in web development and would love to learn from your experience at Google. I have questions about system design and career growth.', 'Web Development Career Guidance', 'I hope to learn about best practices in web development, how to prepare for technical interviews, and what it takes to work at top tech companies.', '2025-09-16 14:30:00', '2025-09-20 15:00:00', '2025-09-20 16:05:00'),(1, 1, 'Software Engineering Interview Prep', 'I have upcoming interviews and would appreciate guidance on technical preparation.', 'I hope to improve my problem-solving approach and communication during interviews.', 'completed', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY), 60, 'Alex was incredibly helpful in preparing me for technical interviews. The mock interview session was especially valuable!'),

(2, 2, 'Product Management Case Studies', 'I would like to discuss real-world product management challenges and solutions.', 'I hope to learn from your experience with complex product decisions.', 'completed', DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY), 75, NULL),

-- Request 2: Scheduled mentorship (upcoming)(3, 1, 'Career Transition Strategies', 'I''m considering changing my focus area and would appreciate advice.', 'I hope to get insights on effective transition approaches and potential challenges.', 'completed', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 60, 'Great session! I received practical advice that helped me make decisions about my career direction.');

(2, 2, '2025-10-01 09:30:00', 'scheduled', 'Hello Robert! I\'m a sophomore studying data science and I\'m fascinated by machine learning. Your research background and industry experience would be invaluable to me.', 'Machine Learning and AI Career Path', 'I want to understand how to balance academic research with practical industry skills, and learn about ML career opportunities.', '2025-10-02 11:00:00', '2025-10-18 14:00:00', NULL),

-- 5. Rejected requests

-- Request 3: Accepted, awaiting student to select time slotINSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) VALUES

(3, 3, '2025-10-10 14:20:00', 'accepted', 'Hi Dr. Thompson! I\'m transitioning from business to product management and would greatly appreciate your guidance. Your experience at Meta is exactly what I need.', 'Breaking into Product Management', 'I want to learn about PM interview preparation, essential skills, and how to build a strong portfolio.', '2025-10-12 09:00:00', NULL, NULL),(2, 3, 'Last-minute Project Help', 'I have a project due tomorrow and need urgent assistance with implementation.', 'I hope you can help me debug and complete my project quickly.', 'rejected', DATE_SUB(NOW(), INTERVAL 15 DAY));



-- Request 4: Pending (just sent, awaiting mentor response)-- Sample time slots for mentorships awaiting student confirmation

(4, 4, '2025-10-15 08:00:00', 'pending', 'Hello William! I\'m passionate about cybersecurity and your CISSP certification and experience in financial security are inspiring. I\'d love to learn from you.', 'Cybersecurity Career and Certifications', 'I hope to understand the cybersecurity career path, important certifications, and practical skills I should develop.', NULL, NULL, NULL),INSERT INTO MentorshipTimeSlots (mentorship_id, start_datetime, end_datetime) VALUES

-- For mentorship_id 3 (Product Management Insights)

-- Request 5: Scheduled mentorship (upcoming)(3, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 2 DAY), INTERVAL 1 HOUR)),

(5, 5, '2025-10-05 16:45:00', 'scheduled', 'Hi Dr. Davis! I\'m very interested in mobile development and would love to learn about building scalable mobile apps. Your Flutter expertise would be amazing!', 'Mobile App Development', 'I want to learn about mobile development best practices, choosing between native and cross-platform, and building apps that scale.', '2025-10-06 10:30:00', '2025-10-22 16:00:00', NULL),(3, DATE_ADD(NOW(), INTERVAL 4 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 4 DAY), INTERVAL 1 HOUR)),

(3, DATE_ADD(NOW(), INTERVAL 6 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 6 DAY), INTERVAL 1 HOUR)),

-- Request 6: Rejected

(6, 6, '2025-10-08 11:30:00', 'rejected', 'Hello John! I\'m a freshman interested in DevOps and cloud computing. I\'d love to learn from your experience at Netflix.', 'Introduction to DevOps and Cloud', 'I want to understand what DevOps is all about and how to start learning cloud platforms.', NULL, NULL, NULL),-- For mentorship_id 4 (Business Strategy for Tech Products)

(4, DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 3 DAY), INTERVAL 45 MINUTE)),

-- Request 7: Scheduled mentorship (upcoming)(4, DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 5 DAY), INTERVAL 45 MINUTE)),

(1, 3, '2025-10-12 13:00:00', 'scheduled', 'Dr. Thompson, I\'ve been thinking about the product side of web development. Could you help me understand the PM perspective?', 'Product Thinking for Engineers', 'Learn how to think like a PM while being an engineer, and understand product strategy.', '2025-10-13 15:20:00', '2025-10-20 10:00:00', NULL),(4, DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 7 DAY), INTERVAL 45 MINUTE));



-- Request 8: Completed with feedback-- Sample notifications

(2, 1, '2025-09-25 10:00:00', 'completed', 'Dr. Anderson, I have a technical interview coming up and would love some guidance on system design questions.', 'Technical Interview Preparation', 'Learn system design fundamentals and interview strategies.', '2025-09-26 09:00:00', '2025-09-30 11:00:00', '2025-09-30 12:25:00');INSERT INTO Notifications (user_id, user_type, related_id, type, message, is_read, created_at) VALUES

-- For undergraduates

-- ============================================================================(4, 'undergraduate', 1, 'mentorship_request', 'Your mentorship request to Alex Johnson has been sent.', 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- 5. INSERT PROPOSED TIME SLOTS(4, 'undergraduate', 3, 'time_slots_offered', 'Samantha Williams has offered time slots for your mentorship session.', 0, DATE_SUB(NOW(), INTERVAL 4 DAY)),

-- ============================================================================(4, 'undergraduate', 6, 'session_scheduled', 'Your mentorship session with Alex Johnson is scheduled for tomorrow.', 0, DATE_SUB(NOW(), INTERVAL 1 DAY)),

INSERT INTO Proposed_Slots (request_id, start_time, end_time, is_selected, is_available, meeting_link, meeting_type, notes) VALUES(4, 'undergraduate', 8, 'session_feedback', 'Please provide feedback for your completed session with Alex Johnson.', 0, DATE_SUB(NOW(), INTERVAL 14 DAY)),

-- Slots for Request 1 (Completed)

(1, '2025-09-20 15:00:00', '2025-09-20 16:00:00', TRUE, TRUE, 'https://meet.google.com/abc-defg-hij', 'virtual', 'Looking forward to discussing web development!'),-- For alumni

(1, '2025-09-21 14:00:00', '2025-09-21 15:00:00', FALSE, FALSE, 'https://meet.google.com/abc-defg-hij', 'virtual', 'Alternative slot'),(1, 'alumni', 1, 'mentorship_request', 'You have received a mentorship request from Emily Parker.', 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),

(2, 'alumni', 3, 'mentorship_accepted', 'You have accepted a mentorship request from Emily Parker.', 0, DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Slots for Request 2 (Scheduled)(1, 'alumni', 6, 'session_scheduled', 'Your mentorship session with David Lee is confirmed for Sep 5, 2025.', 0, DATE_SUB(NOW(), INTERVAL 7 DAY)),

(2, '2025-10-18 14:00:00', '2025-10-18 15:30:00', TRUE, TRUE, 'https://zoom.us/j/123456789', 'virtual', 'We\'ll dive deep into ML concepts'),(1, 'alumni', 8, 'session_feedback', 'Emily Parker has provided feedback for your mentorship session.', 0, DATE_SUB(NOW(), INTERVAL 14 DAY));

(2, '2025-10-19 10:00:00', '2025-10-19 11:30:00', FALSE, TRUE, 'https://zoom.us/j/123456789', 'virtual', 'Morning slot if preferred'),
(2, '2025-10-20 16:00:00', '2025-10-20 17:30:00', FALSE, TRUE, 'https://zoom.us/j/123456789', 'virtual', 'Afternoon alternative'),

-- Slots for Request 3 (Accepted - Student needs to select)
(3, '2025-10-19 13:00:00', '2025-10-19 14:00:00', FALSE, TRUE, 'https://meet.google.com/xyz-abcd-efg', 'virtual', 'Perfect for PM discussion'),
(3, '2025-10-21 15:00:00', '2025-10-21 16:00:00', FALSE, TRUE, 'https://meet.google.com/xyz-abcd-efg', 'virtual', 'Mid-week option'),
(3, '2025-10-23 11:00:00', '2025-10-23 12:00:00', FALSE, TRUE, 'https://meet.google.com/xyz-abcd-efg', 'virtual', 'Later in the week'),

-- Slots for Request 5 (Scheduled)
(5, '2025-10-22 16:00:00', '2025-10-22 17:00:00', TRUE, TRUE, 'https://teams.microsoft.com/meet/123', 'virtual', 'Mobile dev session'),
(5, '2025-10-24 14:00:00', '2025-10-24 15:00:00', FALSE, FALSE, 'https://teams.microsoft.com/meet/123', 'virtual', 'Alternative time'),

-- Slots for Request 7 (Scheduled)
(7, '2025-10-20 10:00:00', '2025-10-20 11:00:00', TRUE, TRUE, 'https://meet.google.com/product-eng-meet', 'virtual', 'Product thinking session'),
(7, '2025-10-21 09:00:00', '2025-10-21 10:00:00', FALSE, TRUE, 'https://meet.google.com/product-eng-meet', 'virtual', 'Alternative slot'),

-- Slots for Request 8 (Completed)
(8, '2025-09-30 11:00:00', '2025-09-30 12:30:00', TRUE, TRUE, 'https://meet.google.com/interview-prep', 'virtual', 'System design interview prep'),
(8, '2025-10-01 13:00:00', '2025-10-01 14:30:00', FALSE, FALSE, 'https://meet.google.com/interview-prep', 'virtual', 'Backup time');

-- ============================================================================
-- 6. INSERT MENTORSHIP SESSIONS
-- ============================================================================
INSERT INTO Mentorship_Sessions (request_id, mentee_id, mentor_id, slot_id, scheduled_time, end_time, status, meeting_link, meeting_type, actual_start_time, actual_end_time, duration_minutes, session_notes, completed_at) VALUES
-- Session 1: Completed
(1, 1, 1, 1, '2025-09-20 15:00:00', '2025-09-20 16:00:00', 'completed', 'https://meet.google.com/abc-defg-hij', 'virtual', '2025-09-20 15:02:00', '2025-09-20 16:05:00', 63, 'Great discussion about web development best practices. Covered system design basics and career advice. Sarah showed strong fundamentals.', '2025-09-20 16:05:00'),

-- Session 2: Scheduled (upcoming)
(2, 2, 2, 3, '2025-10-18 14:00:00', '2025-10-18 15:30:00', 'scheduled', 'https://zoom.us/j/123456789', 'virtual', NULL, NULL, NULL, NULL, NULL),

-- Session 3: Scheduled (upcoming)
(5, 5, 5, 9, '2025-10-22 16:00:00', '2025-10-22 17:00:00', 'scheduled', 'https://teams.microsoft.com/meet/123', 'virtual', NULL, NULL, NULL, NULL, NULL),

-- Session 4: Scheduled (upcoming)
(7, 1, 3, 11, '2025-10-20 10:00:00', '2025-10-20 11:00:00', 'scheduled', 'https://meet.google.com/product-eng-meet', 'virtual', NULL, NULL, NULL, NULL, NULL),

-- Session 5: Completed
(8, 2, 1, 13, '2025-09-30 11:00:00', '2025-09-30 12:30:00', 'completed', 'https://meet.google.com/interview-prep', 'virtual', '2025-09-30 11:00:00', '2025-09-30 12:25:00', 85, 'Excellent system design discussion. Michael has great analytical skills. Covered designing a URL shortener and discussed scalability patterns.', '2025-09-30 12:25:00');

-- ============================================================================
-- 7. INSERT FEEDBACK
-- ============================================================================
INSERT INTO Feedback (session_id, reviewer_id, reviewee_id, reviewer_role, rating, comment, would_recommend, skills_gained, goals_met, mentee_prepared, mentee_engaged, submitted_at) VALUES
-- Feedback for Session 1 from Mentee Sarah
(1, 1, 7, 'mentee', 5, 'Dr. Anderson was absolutely amazing! She provided clear explanations and practical advice that I can apply immediately. Her insights into system design were invaluable.', TRUE, 'System design fundamentals, Technical interview preparation, Career planning', TRUE, NULL, NULL, '2025-09-20 18:30:00'),

-- Feedback for Session 1 from Mentor Jennifer
(1, 7, 1, 'mentor', 5, 'Sarah came well-prepared with specific questions. She was engaged throughout and took detailed notes. Very impressive for a junior!', TRUE, NULL, NULL, TRUE, TRUE, '2025-09-20 20:00:00'),

-- Feedback for Session 5 from Mentee Michael
(5, 2, 7, 'mentee', 5, 'Fantastic session! Dr. Anderson helped me understand complex system design patterns in a simple way. I feel much more confident about my upcoming interview.', TRUE, 'System design patterns, Scalability concepts, Interview strategies', TRUE, NULL, NULL, '2025-09-30 14:00:00'),

-- Feedback for Session 5 from Mentor Jennifer
(5, 7, 2, 'mentor', 5, 'Michael has excellent analytical thinking. He grasped system design concepts quickly and asked thoughtful questions. He will do great in his interview!', TRUE, NULL, NULL, TRUE, TRUE, '2025-09-30 15:30:00');

-- ============================================================================
-- 8. INSERT NOTIFICATIONS
-- ============================================================================
INSERT INTO Notifications (user_id, type, title, message, related_id, related_type, action_url, is_read, priority, created_at, read_at) VALUES
-- Notifications for Mentee Sarah (user_id: 1)
(1, 'request_accepted', 'Mentorship Request Accepted!', 'Dr. Jennifer Anderson has accepted your mentorship request. Time slots are now available for you to choose.', 1, 'request', '/mentorships/schedule/1', TRUE, 'high', '2025-09-16 14:30:00', '2025-09-16 15:00:00'),
(1, 'session_scheduled', 'Session Scheduled!', 'Your mentorship session with Dr. Jennifer Anderson is scheduled for Sep 20, 2025 at 3:00 PM.', 1, 'session', '/mentorships/sessions/1', TRUE, 'high', '2025-09-16 16:00:00', '2025-09-16 16:15:00'),
(1, 'session_reminder_24h', 'Session Reminder', 'Your mentorship session with Dr. Jennifer Anderson is tomorrow at 3:00 PM.', 1, 'session', '/mentorships/sessions/1', TRUE, 'normal', '2025-09-19 15:00:00', '2025-09-19 16:30:00'),
(1, 'session_completed', 'Session Completed', 'Your session with Dr. Jennifer Anderson has been completed. Please provide feedback.', 1, 'session', '/mentorships/feedback/1', TRUE, 'normal', '2025-09-20 16:05:00', '2025-09-20 17:00:00'),
(1, 'feedback_received', 'Feedback Received', 'Dr. Jennifer Anderson has provided feedback on your recent session.', 1, 'feedback', '/mentorships/sessions/1', TRUE, 'low', '2025-09-20 20:00:00', '2025-09-21 09:00:00'),
(1, 'session_scheduled', 'Session Scheduled!', 'Your mentorship session with Dr. Lisa Thompson is scheduled for Oct 20, 2025 at 10:00 AM.', 4, 'session', '/mentorships/sessions/4', FALSE, 'high', '2025-10-13 15:30:00', NULL),

-- Notifications for Mentee Michael (user_id: 2)
(2, 'request_accepted', 'Mentorship Request Accepted!', 'Robert Taylor has accepted your mentorship request. Please select a time slot.', 2, 'request', '/mentorships/schedule/2', TRUE, 'high', '2025-10-02 11:00:00', '2025-10-02 11:30:00'),
(2, 'session_scheduled', 'Session Scheduled!', 'Your mentorship session with Robert Taylor is scheduled for Oct 18, 2025 at 2:00 PM.', 2, 'session', '/mentorships/sessions/2', TRUE, 'high', '2025-10-02 13:00:00', '2025-10-02 13:45:00'),
(2, 'session_reminder_24h', 'Session Reminder', 'Your mentorship session with Robert Taylor is in 2 days!', 2, 'session', '/mentorships/sessions/2', FALSE, 'normal', '2025-10-16 14:00:00', NULL),

-- Notifications for Mentee Emily (user_id: 3)
(3, 'request_accepted', 'Mentorship Request Accepted!', 'Dr. Lisa Thompson has accepted your mentorship request.', 3, 'request', '/mentorships/schedule/3', FALSE, 'high', '2025-10-12 09:00:00', NULL),
(3, 'slots_proposed', 'Time Slots Available', 'Dr. Lisa Thompson has proposed 3 time slots. Please select one soon!', 3, 'request', '/mentorships/schedule/3', FALSE, 'high', '2025-10-12 09:05:00', NULL),

-- Notifications for Mentee James (user_id: 4)
(4, 'request_received', 'Request Submitted', 'Your mentorship request has been sent to William Garcia.', 4, 'request', '/mentorships/requests/4', TRUE, 'normal', '2025-10-15 08:00:00', '2025-10-15 08:01:00'),

-- Notifications for Mentor Jennifer (user_id: 7)
(7, 'request_received', 'New Mentorship Request', 'Sarah Johnson has requested you as a mentor.', 1, 'request', '/mentorships/requests/1', TRUE, 'high', '2025-09-15 10:00:00', '2025-09-15 11:00:00'),
(7, 'session_scheduled', 'Session Confirmed', 'Your session with Sarah Johnson is scheduled for Sep 20, 2025 at 3:00 PM.', 1, 'session', '/mentorships/sessions/1', TRUE, 'high', '2025-09-16 16:00:00', '2025-09-16 17:00:00'),
(7, 'feedback_received', 'Feedback Received', 'Sarah Johnson has provided feedback on your session.', 1, 'feedback', '/mentorships/sessions/1', TRUE, 'low', '2025-09-20 18:30:00', '2025-09-21 08:00:00'),

-- Notifications for Mentor Robert (user_id: 8)
(8, 'request_received', 'New Mentorship Request', 'Michael Chen has requested you as a mentor.', 2, 'request', '/mentorships/requests/2', TRUE, 'high', '2025-10-01 09:30:00', '2025-10-01 10:00:00'),
(8, 'session_scheduled', 'Session Confirmed', 'Your session with Michael Chen is scheduled for Oct 18, 2025.', 2, 'session', '/mentorships/sessions/2', TRUE, 'high', '2025-10-02 13:00:00', '2025-10-02 14:00:00');

-- ============================================================================
-- 9. INSERT MESSAGES
-- ============================================================================
INSERT INTO Messages (request_id, sender_id, receiver_id, message, is_read, sent_at, read_at) VALUES
-- Conversation for Request 1 (Sarah & Jennifer)
(1, 1, 7, 'Hi Dr. Anderson! Thank you so much for accepting my request. I\'m really excited to learn from you!', TRUE, '2025-09-16 15:30:00', '2025-09-16 16:00:00'),
(1, 7, 1, 'Hi Sarah! I\'m happy to help. I\'ve added some time slots - please pick one that works for you.', TRUE, '2025-09-16 16:05:00', '2025-09-16 16:30:00'),
(1, 1, 7, 'Perfect! I\'ve selected Friday at 3 PM. Looking forward to it!', TRUE, '2025-09-16 16:35:00', '2025-09-16 17:00:00'),
(1, 1, 7, 'Thank you for the amazing session today! Your advice was incredibly helpful.', TRUE, '2025-09-20 16:15:00', '2025-09-20 18:00:00'),

-- Conversation for Request 2 (Michael & Robert)
(2, 2, 8, 'Hello Robert! Thanks for accepting. I have so many questions about ML!', TRUE, '2025-10-02 11:45:00', '2025-10-02 12:00:00'),
(2, 8, 2, 'Hi Michael! Happy to help. I\'ve proposed several time slots. Let me know which works best.', TRUE, '2025-10-02 12:15:00', '2025-10-02 13:00:00'),
(2, 2, 8, 'Friday Oct 18 at 2 PM works perfectly for me!', TRUE, '2025-10-02 13:05:00', '2025-10-02 14:00:00'),
(2, 8, 2, 'Excellent! Come prepared with questions!', TRUE, '2025-10-02 14:30:00', '2025-10-02 15:00:00'),

-- Conversation for Request 3 (Emily & Lisa)
(3, 3, 9, 'Dr. Thompson, thank you so much for accepting my request!', TRUE, '2025-10-12 10:00:00', '2025-10-12 11:00:00'),
(3, 9, 3, 'You\'re welcome, Emily! I\'ve added some time slots - please select one.', TRUE, '2025-10-12 11:30:00', '2025-10-12 12:00:00'),
(3, 3, 9, 'I\'m checking my schedule and will confirm by tomorrow!', FALSE, '2025-10-12 13:00:00', NULL),

-- Conversation for Request 5 (Olivia & Maria)
(5, 5, 11, 'Dr. Davis! Thank you for accepting! I\'m so excited about mobile development.', TRUE, '2025-10-06 11:30:00', '2025-10-06 12:00:00'),
(5, 11, 5, 'Happy to help, Olivia! Pick your favorite time slot!', TRUE, '2025-10-06 12:15:00', '2025-10-06 12:30:00'),
(5, 5, 11, 'Tuesday Oct 22 at 4 PM is perfect!', TRUE, '2025-10-06 12:45:00', '2025-10-06 13:00:00');

-- ============================================================================
-- DATA SUMMARY
-- ============================================================================
-- Users: 13 total (6 undergraduates, 6 alumni, 1 admin)
-- Mentor Profiles: 6 mentors (5 available, 1 unavailable)
-- Mentee Profiles: 6 active mentees
-- Mentorship Requests: 8 requests covering all statuses
--   - 2 Completed
--   - 3 Scheduled (upcoming)
--   - 1 Accepted (awaiting student selection)
--   - 1 Pending (awaiting mentor response)
--   - 1 Rejected
-- Proposed Slots: 14 time slots
-- Mentorship Sessions: 5 sessions (2 completed, 3 scheduled)
-- Feedback: 4 feedback entries (from both mentees and mentors)
-- Notifications: 20+ notifications covering various event types
-- Messages: 15+ messages showing realistic conversations
-- ============================================================================

SELECT 'Dummy data inserted successfully!' AS Status;
