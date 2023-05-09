CREATE DATABASE VinylsWrappedDB;

USE VinylsWrappedDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    display_name VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE vinyls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    cover_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE user_vinyls (
    user_id INT NOT NULL,
    vinyl_id INT NOT NULL,
    PRIMARY KEY (user_id, vinyl_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vinyl_id) REFERENCES vinyls(id)
);

CREATE TABLE play_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vinyl_id INT NOT NULL,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (vinyl_id) REFERENCES vinyls(id)
);
