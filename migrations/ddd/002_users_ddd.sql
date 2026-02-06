-- DDD-compliant users database  
CREATE TABLE users (
    id TEXT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role VARCHAR(50) DEFAULT 'ROLE_USER',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
