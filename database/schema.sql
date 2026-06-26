CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,
    secure_password VARCHAR(255) NOT NULL,

    role ENUM('user','admin') DEFAULT 'user',

    login_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,

    last_login DATETIME NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50),
    success BOOLEAN NOT NULL,

    method ENUM(
        'vulnerable',
        'secure',
        'registration'
    ) NOT NULL,

    ip_address VARCHAR(45),
    user_agent TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attack_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,

    attack_type VARCHAR(100) NOT NULL,

    username VARCHAR(50),

    payload TEXT,

    ip_address VARCHAR(45),

    user_agent TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);