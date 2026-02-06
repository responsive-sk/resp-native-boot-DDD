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
('40000000-0000-0000-0000-000000000001', 'admin', 'admin@example.com', 'admin'),
('40000000-0000-0000-0000-000000000002', 'editor', 'editor@example.com', 'editor'),
('40000000-0000-0000-0000-000000000003', 'author', 'author@example.com', 'author');

-- Sample Categories
INSERT OR IGNORE INTO categories (id, name, slug, description, created_at, updated_at) VALUES
('10000000-0000-0000-0000-000000000001', 'Technology', 'technology', 'Latest tech news and innovations', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('10000000-0000-0000-0000-000000000002', 'Programming', 'programming', 'Programming tutorials and best practices', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('10000000-0000-0000-0000-000000000003', 'Web Development', 'web-development', 'Web dev tips and frameworks', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
('10000000-0000-0000-0000-000000000004', 'Design', 'design', 'UI/UX design principles and trends', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- Sample Tags
INSERT OR IGNORE INTO tags (id, name, slug) VALUES
('20000000-0000-0000-0000-000000000001', 'PHP', 'php'),
('20000000-0000-0000-0000-000000000002', 'JavaScript', 'javascript'),
('20000000-0000-0000-0000-000000000003', 'React', 'react'),
('20000000-0000-0000-0000-000000000004', 'Vue', 'vue'),
('20000000-0000-0000-0000-000000000005', 'Database', 'database'),
('20000000-0000-0000-0000-000000000006', 'API', 'api'),
('20000000-0000-0000-0000-000000000007', 'Tutorial', 'tutorial'),
('20000000-0000-0000-0000-000000000008', 'Best Practices', 'best-practices'),
('20000000-0000-0000-0000-000000000009', 'Performance', 'performance'),
('20000000-0000-0000-0000-000000000010', 'Security', 'security');

-- Sample Articles (10 articles)
INSERT OR IGNORE INTO articles (id, title, slug, excerpt, content, author_id, status, published_at) VALUES
('30000000-0000-0000-0000-000000000001', 'Getting Started with PHP 8', 'getting-started-with-php-8', 'Learn about the new features in PHP 8 and how to use them in your projects.', 'PHP 8 brings many exciting features that will make your code more expressive and efficient. In this article, we explore the most important additions like named arguments, constructor property promotion, and union types. These features will help you write cleaner, more maintainable code.', '40000000-0000-0000-0000-000000000001', 'published', '2024-01-15 10:00:00'),

('30000000-0000-0000-0000-000000000002', 'Modern JavaScript ES6+ Features', 'modern-javascript-es6-features', 'Explore the powerful features of modern JavaScript that will transform your development workflow.', 'JavaScript has evolved significantly with ES6 and beyond. Learn about arrow functions, destructuring, template literals, and async/await. These features make JavaScript more powerful and enjoyable to use in modern web development.', '40000000-0000-0000-0000-000000000001', 'published', '2024-01-20 14:30:00'),

('30000000-0000-0000-0000-000000000003', 'Building RESTful APIs with Node.js', 'building-restful-apis-with-nodejs', 'Learn how to design and implement robust REST APIs using Node.js and Express.', 'Creating RESTful APIs with Node.js is straightforward with the right tools and patterns. This guide covers Express setup, RESTful design principles, error handling, and input validation for building maintainable APIs.', '40000000-0000-0000-0000-000000000002', 'published', '2024-01-25 09:15:00'),

('30000000-0000-0000-0000-000000000004', 'React Hooks: A Complete Guide', 'react-hooks-complete-guide', 'Master React Hooks and learn how to build modern React applications without classes.', 'React Hooks revolutionized how we write React components. Learn about useState, useEffect, custom hooks, and useContext to build modern React applications without classes.', '40000000-0000-0000-0000-000000000002', 'published', '2024-02-01 16:45:00'),

('30000000-0000-0000-0000-000000000005', 'Database Design Best Practices', 'database-design-best-practices', 'Learn essential database design principles for building scalable and maintainable applications.', 'Good database design is crucial for application performance. Learn about normalization, indexing strategies, proper data types, and naming conventions for building scalable databases.', '40000000-0000-0000-0000-000000000003', 'published', '2024-02-05 11:20:00'),

('30000000-0000-0000-0000-000000000006', 'Vue.js 3 Composition API', 'vuejs-3-composition-api', 'Discover the power of Vue.js 3 Composition API and how it compares to the Options API.', 'The Composition API in Vue.js 3 provides a more flexible way to organize component logic. Learn about setup function, reactive references, computed properties, and custom composables.', '40000000-0000-0000-0000-000000000003', 'published', '2024-02-10 13:00:00'),

('30000000-0000-0000-0000-000000000007', 'API Security Best Practices', 'api-security-best-practices', 'Essential security measures every developer should implement when building APIs.', 'Security is critical when building APIs. Learn about authentication, input validation, rate limiting, HTTPS, CORS configuration, and security headers to protect your applications.', '40000000-0000-0000-0000-000000000001', 'published', '2024-02-15 10:30:00'),

('30000000-0000-0000-0000-000000000008', 'Performance Optimization Techniques', 'performance-optimization-techniques', 'Learn how to optimize your web applications for maximum performance.', 'Optimizing web application performance is crucial for user experience. Learn about frontend optimization, database optimization, caching strategies, HTTP caching, and bundle optimization.', '40000000-0000-0000-0000-000000000001', 'published', '2024-02-20 15:00:00'),

('30000000-0000-0000-0000-000000000009', 'Testing Strategies for Modern Apps', 'testing-strategies-modern-apps', 'Comprehensive guide to testing modern web applications with unit, integration, and E2E tests.', 'Testing is essential for maintaining code quality. Learn about unit testing, integration testing, end-to-end testing, the test pyramid, mocking, and test coverage strategies.', '40000000-0000-0000-0000-000000000002', 'published', '2024-02-25 12:15:00'),

('30000000-0000-0000-0000-000000000010', 'Microservices Architecture Guide', 'microservices-architecture-guide', 'Learn how to design, build, and deploy microservices for scalable applications.', 'Microservices architecture offers scalability and flexibility. Learn about service communication, API gateways, data management, containerization, and service discovery for building distributed systems.', '40000000-0000-0000-0000-000000000003', 'published', '2024-03-01 14:00:00');

-- Article-Tag relationships
INSERT OR IGNORE INTO article_tags (article_id, tag_id) VALUES
-- Article 1 (PHP 8)
('30000000-0000-0000-0000-000000000001', '20000000-0000-0000-0000-000000000001'), -- PHP
('30000000-0000-0000-0000-000000000001', '20000000-0000-0000-0000-000000000007'), -- Tutorial
('30000000-0000-0000-0000-000000000001', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 2 (JavaScript)
('30000000-0000-0000-0000-000000000002', '20000000-0000-0000-0000-000000000002'), -- JavaScript
('30000000-0000-0000-0000-000000000002', '20000000-0000-0000-0000-000000000007'), -- Tutorial

-- Article 3 (Node.js APIs)
('30000000-0000-0000-0000-000000000003', '20000000-0000-0000-0000-000000000001'), -- PHP
('30000000-0000-0000-0000-000000000003', '20000000-0000-0000-0000-000000000006'), -- API
('30000000-0000-0000-0000-000000000003', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 4 (React Hooks)
('30000000-0000-0000-0000-000000000004', '20000000-0000-0000-0000-000000000002'), -- JavaScript
('30000000-0000-0000-0000-000000000004', '20000000-0000-0000-0000-000000000003'), -- React
('30000000-0000-0000-0000-000000000004', '20000000-0000-0000-0000-000000000007'), -- Tutorial

-- Article 5 (Database Design)
('30000000-0000-0000-0000-000000000005', '20000000-0000-0000-0000-000000000005'), -- Database
('30000000-0000-0000-0000-000000000005', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 6 (Vue.js)
('30000000-0000-0000-0000-000000000006', '20000000-0000-0000-0000-000000000002'), -- JavaScript
('30000000-0000-0000-0000-000000000006', '20000000-0000-0000-0000-000000000004'), -- Vue
('30000000-0000-0000-0000-000000000006', '20000000-0000-0000-0000-000000000007'), -- Tutorial

-- Article 7 (API Security)
('30000000-0000-0000-0000-000000000007', '20000000-0000-0000-0000-000000000006'), -- API
('30000000-0000-0000-0000-000000000007', '20000000-0000-0000-0000-000000000010'), -- Security
('30000000-0000-0000-0000-000000000007', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 8 (Performance)
('30000000-0000-0000-0000-000000000008', '20000000-0000-0000-0000-000000000009'), -- Performance
('30000000-0000-0000-0000-000000000008', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 9 (Testing)
('30000000-0000-0000-0000-000000000009', '20000000-0000-0000-0000-000000000007'), -- Tutorial
('30000000-0000-0000-0000-000000000009', '20000000-0000-0000-0000-000000000008'), -- Best Practices

-- Article 10 (Microservices)
('30000000-0000-0000-0000-000000000010', '20000000-0000-0000-0000-000000000006'), -- API
('30000000-0000-0000-0000-000000000010', '20000000-0000-0000-0000-000000000005'), -- Database
('30000000-0000-0000-0000-000000000010', '20000000-0000-0000-0000-000000000009'); -- Performance
