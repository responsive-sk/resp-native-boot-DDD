-- ARTICLES DATABASE
CREATE TABLE IF NOT EXISTS authors (
    id TEXT PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    role TEXT NOT NULL DEFAULT 'author',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS articles (
    id TEXT PRIMARY KEY,
    title TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    author_id TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    featured_image_url TEXT,
    meta_title TEXT,
    meta_description TEXT,
    published_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);

CREATE TABLE IF NOT EXISTS categories (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS tags (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS article_tags (
    article_id TEXT NOT NULL,
    tag_id TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Indexes
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_author_id ON articles(author_id);
CREATE INDEX idx_articles_published_at ON articles(published_at);
CREATE INDEX idx_articles_created_at ON articles(created_at);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_tags_slug ON tags(slug);
CREATE INDEX idx_article_tags_article_id ON article_tags(article_id);
CREATE INDEX idx_article_tags_tag_id ON article_tags(tag_id);

CREATE INDEX idx_categories_updated_at ON categories(updated_at);

-- Triggers
CREATE TRIGGER IF NOT EXISTS update_articles_timestamp 
AFTER UPDATE ON articles 
FOR EACH ROW
BEGIN
    UPDATE articles SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

CREATE TRIGGER IF NOT EXISTS update_categories_timestamp 
AFTER UPDATE ON categories 
FOR EACH ROW
BEGIN
    UPDATE categories SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

-- Sample Authors
INSERT OR IGNORE INTO authors (id, username, email, role) VALUES
('1', 'admin', 'admin@example.com', 'admin'),
('2', 'editor', 'editor@example.com', 'editor'),
('3', 'author', 'author@example.com', 'author');

-- Sample Categories
INSERT OR IGNORE INTO categories (id, name, slug, description, created_at, updated_at) VALUES
('1', 'Technology', 'technology', 'Latest tech news and innovations', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('2', 'Programming', 'programming', 'Programming tutorials and best practices', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('3', 'Web Development', 'web-development', 'Web dev tips and frameworks', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('4', 'Design', 'design', 'UI/UX design principles and trends', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- Sample Tags
INSERT OR IGNORE INTO tags (id, name, slug) VALUES
('1', 'PHP', 'php'),
('2', 'JavaScript', 'javascript'),
('3', 'React', 'react'),
('4', 'Vue', 'vue'),
('5', 'Database', 'database'),
('6', 'API', 'api'),
('7', 'Tutorial', 'tutorial'),
('8', 'Best Practices', 'best-practices'),
('9', 'Performance', 'performance'),
('10', 'Security', 'security');

-- Sample Articles (10 articles)
INSERT OR IGNORE INTO articles (id, title, slug, excerpt, content, author_id, status, published_at) VALUES
('1', 'Getting Started with PHP 8', 'getting-started-with-php-8', 'Learn about the new features in PHP 8 and how to use them in your projects.', 'PHP 8 brings many exciting features that will make your code more expressive and efficient. In this article, we explore the most important additions like named arguments, constructor property promotion, and union types. These features will help you write cleaner, more maintainable code.', '1', 'published', '2024-01-15 10:00:00'),

('2', 'Modern JavaScript ES6+ Features', 'modern-javascript-es6-features', 'Explore the powerful features of modern JavaScript that will transform your development workflow.', 'JavaScript has evolved significantly with ES6 and beyond. Learn about arrow functions, destructuring, template literals, and async/await. These features make JavaScript more powerful and enjoyable to use in modern web development.', '1', 'published', '2024-01-20 14:30:00'),

('3', 'Building RESTful APIs with Node.js', 'building-restful-apis-with-nodejs', 'Learn how to design and implement robust REST APIs using Node.js and Express.', 'Creating RESTful APIs with Node.js is straightforward with the right tools and patterns. This guide covers Express setup, RESTful design principles, error handling, and input validation for building maintainable APIs.', '2', 'published', '2024-01-25 09:15:00'),

('4', 'React Hooks: A Complete Guide', 'react-hooks-complete-guide', 'Master React Hooks and learn how to build modern React applications without classes.', 'React Hooks revolutionized how we write React components. Learn about useState, useEffect, custom hooks, and useContext to build modern React applications without classes.', '2', 'published', '2024-02-01 16:45:00'),

('5', 'Database Design Best Practices', 'database-design-best-practices', 'Learn essential database design principles for building scalable and maintainable applications.', 'Good database design is crucial for application performance. Learn about normalization, indexing strategies, proper data types, and naming conventions for building scalable databases.', '3', 'published', '2024-02-05 11:20:00'),

('6', 'Vue.js 3 Composition API', 'vuejs-3-composition-api', 'Discover the power of Vue.js 3 Composition API and how it compares to the Options API.', 'The Composition API in Vue.js 3 provides a more flexible way to organize component logic. Learn about setup function, reactive references, computed properties, and custom composables.', '3', 'published', '2024-02-10 13:00:00'),

('7', 'API Security Best Practices', 'api-security-best-practices', 'Essential security measures every developer should implement when building APIs.', 'Security is critical when building APIs. Learn about authentication, input validation, rate limiting, HTTPS, CORS configuration, and security headers to protect your applications.', '1', 'published', '2024-02-15 10:30:00'),

('8', 'Performance Optimization Techniques', 'performance-optimization-techniques', 'Learn how to optimize your web applications for maximum performance.', 'Optimizing web application performance is crucial for user experience. Learn about frontend optimization, database optimization, caching strategies, HTTP caching, and bundle optimization.', '1', 'published', '2024-02-20 15:00:00'),

('9', 'Testing Strategies for Modern Apps', 'testing-strategies-modern-apps', 'Comprehensive guide to testing modern web applications with unit, integration, and E2E tests.', 'Testing is essential for maintaining code quality. Learn about unit testing, integration testing, end-to-end testing, the test pyramid, mocking, and test coverage strategies.', '2', 'published', '2024-02-25 12:15:00'),

('10', 'Microservices Architecture Guide', 'microservices-architecture-guide', 'Learn how to design, build, and deploy microservices for scalable applications.', 'Microservices architecture offers scalability and flexibility. Learn about service communication, API gateways, data management, containerization, and service discovery for building distributed systems.', '3', 'published', '2024-03-01 14:00:00');

-- Article-Tag relationships
INSERT OR IGNORE INTO article_tags (article_id, tag_id) VALUES
-- Article 1 (PHP 8)
('1', '1'), -- PHP
('1', '7'), -- Tutorial
('1', '8'), -- Best Practices

-- Article 2 (JavaScript)
('2', '2'), -- JavaScript
('2', '7'), -- Tutorial

-- Article 3 (Node.js APIs)
('3', '1'), -- PHP
('3', '6'), -- API
('3', '8'), -- Best Practices

-- Article 4 (React Hooks)
('4', '2'), -- JavaScript
('4', '3'), -- React
('4', '7'), -- Tutorial

-- Article 5 (Database Design)
('5', '5'), -- Database
('5', '8'), -- Best Practices

-- Article 6 (Vue.js)
('6', '2'), -- JavaScript
('6', '4'), -- Vue
('6', '7'), -- Tutorial

-- Article 7 (API Security)
('7', '6'), -- API
('7', '10'), -- Security
('7', '8'), -- Best Practices

-- Article 8 (Performance)
('8', '9'), -- Performance
('8', '8'), -- Best Practices

-- Article 9 (Testing)
('9', '7'), -- Tutorial
('9', '8'), -- Best Practices

-- Article 10 (Microservices)
('10', '6'), -- API
('10', '5'), -- Database
('10', '9'); -- Performance
