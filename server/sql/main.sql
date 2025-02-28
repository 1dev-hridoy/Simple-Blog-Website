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


