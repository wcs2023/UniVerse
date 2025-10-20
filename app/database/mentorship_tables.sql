-- SQL Tables for UniVerse Mentorship Feature

-- Users Table - The base table for all application users
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('undergraduate', 'alumni', 'admin', 'company') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alumni Table - Stores profile information for alumni users
CREATE TABLE IF NOT EXISTS Alumni (
    alumni_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    title VARCHAR(255) NULL, -- Job title
    company VARCHAR(255) NULL, -- Company name
    bio TEXT NULL, -- Professional bio
    mentorship_status ENUM('available', 'unavailable') DEFAULT 'unavailable',
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- AlumniExperience Table - Stores work experience for alumni
CREATE TABLE IF NOT EXISTS AlumniExperience (
    experience_id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    start_date VARCHAR(50) NOT NULL,
    end_date VARCHAR(50) NOT NULL,
    description TEXT NULL,
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE
);

-- AlumniSkills Table - Stores skills and expertise for alumni
CREATE TABLE IF NOT EXISTS AlumniSkills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE
);

-- Undergraduates Table - Stores profile information for undergraduate users
CREATE TABLE IF NOT EXISTS Undergraduates (
    undergraduate_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    program VARCHAR(255) NULL, -- Degree program
    year VARCHAR(50) NULL, -- Year of study
    major VARCHAR(255) NULL, -- Field of study
    interests TEXT NULL, -- Career/academic interests
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Mentorships Table - The main table to track the status of a mentorship relationship
CREATE TABLE IF NOT EXISTS Mentorships (
    mentorship_id INT AUTO_INCREMENT PRIMARY KEY,
    undergraduate_id INT NOT NULL,
    alumni_id INT NOT NULL,
    topic VARCHAR(255) NULL,
    message TEXT NULL,
    expectations TEXT NULL,
    status ENUM('pending', 'awaiting_student_confirmation', 'scheduled', 'completed', 'rejected', 'canceled') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    scheduled_date DATETIME NULL,
    duration INT DEFAULT 60, -- Duration in minutes
    feedback TEXT NULL, -- Student feedback
    mentor_feedback TEXT NULL, -- Alumni feedback
    FOREIGN KEY (undergraduate_id) REFERENCES Undergraduates(undergraduate_id) ON DELETE CASCADE,
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE,
    INDEX (undergraduate_id),
    INDEX (alumni_id)
);

-- MentorshipTimeSlots Table - Stores the specific time slots offered by a mentor
CREATE TABLE IF NOT EXISTS MentorshipTimeSlots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    mentorship_id INT NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    is_booked BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY (mentorship_id) REFERENCES Mentorships(mentorship_id) ON DELETE CASCADE,
    INDEX (mentorship_id)
);

-- Notifications Table - Stores notifications for users
CREATE TABLE IF NOT EXISTS Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('undergraduate', 'alumni', 'admin', 'company') NOT NULL,
    related_id INT NULL, -- ID of the related entity (e.g. mentorship_id)
    type ENUM('mentorship_request', 'mentorship_accepted', 'mentorship_rejected', 'time_slots_offered', 'session_scheduled', 'session_reminder', 'session_feedback') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id, user_type)
);

-- Sample data for testing (uncomment to use)
/*
-- Sample alumni with skills and experience
INSERT INTO Users (email, password, role) VALUES
('john.doe@example.com', '$2y$10$Hzc7V5H5Kl9QJm5X5H5H5OZjZ5H5Kl9QJm5X5H5H5OZjZ5H5Kl9Q', 'alumni'),
('jane.smith@example.com', '$2y$10$Hzc7V5H5Kl9QJm5X5H5H5OZjZ5H5Kl9QJm5X5H5H5OZjZ5H5Kl9Q', 'alumni');

INSERT INTO Alumni (user_id, first_name, last_name, title, company, bio, mentorship_status) VALUES
(1, 'John', 'Doe', 'Senior Software Engineer', 'Tech Solutions Inc.', 'Experienced software engineer with 10+ years in the industry. Passionate about mentoring and helping students navigate their career path.', 'available'),
(2, 'Jane', 'Smith', 'Product Manager', 'Innovation Labs', 'Product management professional with experience in startups and enterprise companies. Loves helping students understand the tech industry.', 'available');

INSERT INTO AlumniExperience (alumni_id, title, company, start_date, end_date, description) VALUES
(1, 'Senior Software Engineer', 'Tech Solutions Inc.', 'Jan 2018', 'Present', 'Leading development of enterprise applications.'),
(1, 'Software Developer', 'Code Masters', 'Mar 2015', 'Dec 2017', 'Worked on frontend and backend development.'),
(2, 'Product Manager', 'Innovation Labs', 'Jun 2019', 'Present', 'Managing product roadmap and leading a team of developers.'),
(2, 'Associate Product Manager', 'StartUp Co', 'Aug 2017', 'May 2019', 'Helped launch 3 successful products.');

INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES
(1, 'JavaScript'),
(1, 'React'),
(1, 'Node.js'),
(1, 'Python'),
(2, 'Product Management'),
(2, 'Agile'),
(2, 'UX Design'),
(2, 'Data Analysis');

-- Sample undergraduates
INSERT INTO Users (email, password, role) VALUES
('student1@university.edu', '$2y$10$Hzc7V5H5Kl9QJm5X5H5H5OZjZ5H5Kl9QJm5X5H5H5OZjZ5H5Kl9Q', 'undergraduate'),
(
'student2@university.edu', '$2y$10$Hzc7V5H5Kl9QJm5X5H5H5OZjZ5H5Kl9QJm5X5H5H5OZjZ5H5Kl9Q', 'undergraduate');

INSERT INTO Undergraduates (user_id, first_name, last_name, program, year, major, interests) VALUES
(3, 'Mike', 'Johnson', 'Bachelor of Science', 'Junior', 'Computer Science', 'Software development, AI, Machine Learning'),
(4, 'Sarah', 'Williams', 'Bachelor of Business', 'Senior', 'Business Administration', 'Marketing, Entrepreneurship, Product Management');

-- Sample mentorship requests
INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) VALUES
(1, 1, 'Career Guidance', 'I would like some advice on pursuing a career in software engineering.', 'I hope to gain insights into the industry and learn about potential career paths.', 'pending', NOW()),
(1, 2, 'Academic Advice', 'I need help with choosing my electives for next semester.', 'I would appreciate guidance on which courses would be most beneficial for my career goals.', 'awaiting_student_confirmation', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 1, 'Industry Insights', 'I am interested in learning more about the tech industry.', 'I would like to understand the current trends and challenges in the field.', 'scheduled', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 2, 'Internship Advice', 'I need help with preparing for internship applications.', 'I would like feedback on my resume and advice on interview preparation.', 'completed', DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Sample time slots for the mentorship with status 'awaiting_student_confirmation'
INSERT INTO MentorshipTimeSlots (mentorship_id, start_datetime, end_datetime) VALUES
(2, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 2 DAY), INTERVAL 1 HOUR)),
(2, DATE_ADD(NOW(), INTERVAL 3 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 3 DAY), INTERVAL 1 HOUR)),
(2, DATE_ADD(NOW(), INTERVAL 4 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 4 DAY), INTERVAL 1 HOUR));

-- Sample notifications
INSERT INTO Notifications (user_id, user_type, related_id, type, message, is_read, created_at) VALUES
(1, 'undergraduate', 1, 'mentorship_request', 'Your mentorship request to John Doe has been sent.', 0, NOW()),
(1, 'alumni', 1, 'mentorship_request', 'You have received a mentorship request from Mike Johnson.', 0, NOW()),
(1, 'undergraduate', 2, 'time_slots_offered', 'Jane Smith has offered time slots for your mentorship session.', 0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'alumni', 3, 'session_scheduled', 'Your mentorship session with Sarah Williams has been scheduled.', 0, DATE_SUB(NOW(), INTERVAL 3 DAY));
*/
