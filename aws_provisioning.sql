-- Alter existing table to add new columns
ALTER TABLE amis
ADD COLUMN session_id VARCHAR(10) NOT NULL;

-- Create new tables if they do not exist
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS amis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    ami_id VARCHAR(255) NOT NULL,
    ami_tag VARCHAR(255) NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE IF NOT EXISTS schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    num_students INT NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE IF NOT EXISTS instance_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    ami_id VARCHAR(255) NOT NULL,
    instance_id VARCHAR(255) NOT NULL,
    creation_date DATE NOT NULL,
    destruction_date DATE DEFAULT NULL,
    FOREIGN KEY (schedule_id) REFERENCES schedule(id)
);
