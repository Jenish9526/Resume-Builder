CREATE DATABASE IF NOT EXISTS resume_builder
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE resume_builder;

CREATE TABLE IF NOT EXISTS resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(40) DEFAULT '',
    email VARCHAR(150) DEFAULT '',
    address TEXT,
    linkedin VARCHAR(255) DEFAULT '',
    github VARCHAR(255) DEFAULT '',
    objective TEXT,
    education LONGTEXT,
    skills TEXT,
    projects LONGTEXT,
    company VARCHAR(150) DEFAULT '',
    position VARCHAR(150) DEFAULT '',
    experience_date VARCHAR(100) DEFAULT '',
    responsibilities TEXT,
    certifications TEXT,
    achievements TEXT,
    languages VARCHAR(500) DEFAULT '',
    template VARCHAR(30) DEFAULT 'template1',
    photo VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);