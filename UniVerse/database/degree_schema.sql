CREATE TABLE degrees_cutoff(
    id INT AUto_INCREMENT PRIMARY KEY,
    university VARCHAR(255) NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    stream ENUM('maths', 'bio', 'arts', 'commerce', 'tech') NOT NULL,
    district VARCHAR(100) NOT NULL,
    cutoff_marks DECIMAL(6,4) NOT NULL,
    unicode VARCHAR(100) UNIQUE NOT NULL,
    details TEXT,
    year INT DEFAULT 2024,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)