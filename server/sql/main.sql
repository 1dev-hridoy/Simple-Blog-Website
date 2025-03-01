-- hero section table
CREATE TABLE hero_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- footer section table
CREATE TABLE footer_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    twitter_url VARCHAR(255),
    linkedIn_url VARCHAR(255),
    github_url VARCHAR(255),
    copyright_txt TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- user bio
CREATE TABLE bio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    bio TEXT,
    skills TEXT,
    experience TEXT,
    interests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- category table
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    edited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- seo table
CREATE TABLE seo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    keywords TEXT NOT NULL,
    author VARCHAR(255) NOT NULL,
    og_title VARCHAR(255) NOT NULL,
    og_description TEXT NOT NULL,
    og_image VARCHAR(255) NOT NULL,
    og_url VARCHAR(255) NOT NULL,
    twitter_title VARCHAR(255) NOT NULL,
    twitter_description TEXT NOT NULL,
    twitter_image VARCHAR(255) NOT NULL,
    canonical_url VARCHAR(255) NOT NULL,
    schema_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

