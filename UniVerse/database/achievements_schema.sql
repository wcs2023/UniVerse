-- Achievements table for UniVerse
CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('certificate', 'award', 'project', 'activity', 'leadership', 'internship', 'competition') NOT NULL DEFAULT 'certificate',
    date_achieved DATE NOT NULL,
    certificate_url VARCHAR(500) NULL,
    institution VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_date_achieved (date_achieved)
);

-- Sample achievements data
INSERT INTO achievements (user_id, title, description, type, date_achieved, certificate_url, institution) VALUES
(1, 'Community Leadership Certificate', 'Awarded for outstanding leadership and volunteer work in local community initiatives, involving organizing several charity events and leading community development projects.', 'certificate', '2023-12-15', 'https://example.com/cert1', 'Community Development Center'),

(1, 'Robotics Club President', 'Led the university robotics club, guiding the team to develop an autonomous navigation robot that won the regional robotics competition. Managed a team of 15 members and coordinated with faculty advisors.', 'leadership', '2023-09-01', 'https://example.com/robotics', 'University of Colombo'),

(1, 'Capstone Research Project', 'Developed a novel machine learning model for predictive analytics in renewable energy consumption, achieving 95% accuracy. Project was selected for presentation at the national undergraduate research symposium.', 'project', '2024-05-20', 'https://github.com/johndoe/capstone-project', 'Faculty of Science'),

(1, 'Web Development Internship', 'Completed a 3-month internship at TechSolutions Inc., contributing to the front-end development of their main client portal using React.js and improving user experience metrics by 30%.', 'internship', '2023-08-30', 'https://techsolutions.com/intern-certificate', 'TechSolutions Inc.'),

(1, 'Python Programming Course', 'Successfully completed an advanced Python programming course with a focus on data structures and algorithms. Achieved a final grade of A+ and completed 5 practical projects.', 'certificate', '2023-05-20', 'https://coursera.org/cert/python-advanced', 'Coursera'),

(1, 'University Debate Team', 'Member of the university debate team, participating in national competitions and enhancing public speaking and critical thinking skills. Reached semi-finals in the Inter-University Debate Championship.', 'activity', '2022-11-01', NULL, 'University of Colombo'),

(1, 'Excellence in Computer Science', 'Top performer in Computer Science department with highest GPA in the cohort. Recognized for outstanding academic performance and contribution to research projects.', 'award', '2023-11-30', NULL, 'Faculty of Science'),

(1, 'AWS Cloud Practitioner', 'Amazon Web Services Cloud Practitioner Certification demonstrating foundational knowledge of AWS cloud services and best practices for cloud architecture.', 'certificate', '2024-01-15', 'https://aws.amazon.com/verification/ABC123', 'Amazon Web Services'),

(1, 'Hackathon Winner', 'First place winner at the National University Hackathon 2024. Developed an innovative mobile app for sustainable transportation with a team of 4 members in 48 hours.', 'competition', '2024-03-10', 'https://hackathon2024.com/winners', 'National University Alliance'),

(1, 'Volunteer Coordinator', 'Coordinated volunteer activities for local NGO, organizing educational programs for underprivileged children. Led a team of 20 volunteers and impacted over 100 children.', 'leadership', '2023-06-15', NULL, 'Hope Foundation');
