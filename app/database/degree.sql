CREATE TABLE IF NOT EXISTS universities (
    university_id INT PRIMARY KEY AUTO_INCREMENT,
    university_name VARCHAR(255) NOT NULL UNIQUE,
    university_code VARCHAR(50) NOT NULL UNIQUE
);


create table if not exists degree_programs(
    degree_id INT PRIMARY KEY AUTO_INCREMENT,
    degree_name VARCHAR(255) NOT NULL,
    unicode VARCHAR(50) NOT NULL UNIQUE,
    university_id INT NOT NULL,
    stream ENUM ('maths','bio','commerce','arts','tech') NOT NULL,
    details text NOT NULL,
    FOREIGN KEY (university_id) REFERENCES universities(university_id)

    UNIQUE (degree_name, university_id, stream)
);

create table if not EXISTS degree_cutoffs(
    cutoff_id INT PRIMARY KEY AUTO_INCREMENT,
    degree_id INT NOT NULL,
    district VARCHAR(100) NOT NULL,
    cutoff_mark DECIMAL(4,2) NOT NULL,
    year YEAR NOT NULL,
    FOREIGN KEY (degree_id) REFERENCES degree_programs(degree_id),

    UNIQUE (degree_id, district, year)
);