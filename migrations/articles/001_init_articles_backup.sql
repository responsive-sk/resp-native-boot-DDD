-- ARTICLES DATABASE
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
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
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

-- Triggers
CREATE TRIGGER IF NOT EXISTS update_articles_timestamp 
AFTER UPDATE ON articles 
FOR EACH ROW
BEGIN
    UPDATE articles SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

-- Sample Categories
INSERT OR IGNORE INTO categories (id, name, slug, description) VALUES
('10000000-0000-0000-0000-000000000001', 'Technology', 'technology', 'Latest tech news and innovations'),
('10000000-0000-0000-0000-000000000002', 'Programming', 'programming', 'Programming tutorials and best practices'),
('10000000-0000-0000-0000-000000000003', 'Web Development', 'web-development', 'Web dev tips and frameworks'),
('10000000-0000-0000-0000-000000000004', 'Design', 'design', 'UI/UX design principles and trends');

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
('30000000-0000-0000-0000-000000000001', 'Getting Started with PHP 8', 'getting-started-with-php-8', 'Learn about the new features in PHP 8 and how to use them in your projects.', '# Getting Started with PHP 8

PHP 8 brings many exciting features that will make your code more expressive and efficient. In this article, we''ll explore the most important additions.

## Named Arguments
PHP 8 introduces named arguments, allowing you to pass arguments to a function based on their parameter names rather than their position.

```php
function createUser(string $name, bool $isActive = true, ?string $email = null) {
    // ...
}

// Instead of this:
createUser('John', false, 'john@example.com');

// You can now do this:
createUser(name: 'John', email: 'john@example.com', isActive: false);
```

## Constructor Property Promotion
This feature reduces boilerplate code when creating value objects and entities.

```php
// Before PHP 8
class User {
    private string $name;
    private bool $isActive;
    
    public function __construct(string $name, bool $isActive) {
        $this->name = $name;
        $this->isActive = $isActive;
    }
}

// With PHP 8
class User {
    public function __construct(
        private string $name,
        private bool $isActive
    ) {}
}
```

## Union Types
Union types allow you to declare that a variable can be one of several types.

```php
class Number {
    private int|float $number;
    
    public function setNumber(int|float $number): void {
        $this->number = $number;
    }
}
```

These are just a few of the many improvements in PHP 8. Start using them today to write cleaner, more maintainable code!', '00000000-0000-0000-0000-000000000001', 'published', '2024-01-15 10:00:00'),

('30000000-0000-0000-0000-000000000002', 'Modern JavaScript ES6+ Features', 'modern-javascript-es6-features', 'Explore the powerful features of modern JavaScript that will transform your development workflow.', '# Modern JavaScript ES6+ Features

JavaScript has evolved significantly with ES6 and beyond. Let''s dive into the features that will make you more productive.

## Arrow Functions
Arrow functions provide a more concise syntax and lexical `this` binding.

```javascript
// Traditional function
const add = function(a, b) {
    return a + b;
};

// Arrow function
const add = (a, b) => a + b;

// Single parameter (no parentheses needed)
const double = x => x * 2;
```

## Destructuring
Extract values from arrays and objects with ease.

```javascript
// Array destructuring
const [first, second, third] = [1, 2, 3];

// Object destructuring
const {name, age, email} = {
    name: 'John',
    age: 30,
    email: 'john@example.com'
};
```

## Template Literals
Create strings with embedded expressions.

```javascript
const name = 'World';
const greeting = `Hello, ${name}!`;
const multiLine = `This is a
multi-line string
without escape characters.`;
```

## Async/Await
Write asynchronous code that looks synchronous.

```javascript
async function fetchUserData(userId) {
    try {
        const response = await fetch(`/api/users/${userId}`);
        const user = await response.json();
        return user;
    } catch (error) {
        console.error('Error fetching user:', error);
    }
}
```

These features make JavaScript more powerful and enjoyable to use!', '00000000-0000-0000-0000-000000000001', 'published', '2024-01-20 14:30:00'),

('30000000-0000-0000-0000-000000000003', 'Building RESTful APIs with Node.js', 'building-restful-apis-with-nodejs', 'Learn how to design and implement robust REST APIs using Node.js and Express.', '# Building RESTful APIs with Node.js

Creating RESTful APIs with Node.js is straightforward with the right tools and patterns. This guide will walk you through the essentials.

## Setting Up Express
First, let''s create a basic Express server:

```javascript
const express = require('express');
const app = express();

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Routes
app.get('/api/users', async (req, res) => {
    try {
        const users = await User.findAll();
        res.json(users);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
```

## RESTful Design Principles
Follow these principles for clean APIs:

- Use HTTP verbs correctly (GET, POST, PUT, DELETE)
- Use nouns for resource names (/users, /articles)
- Implement proper status codes
- Version your APIs (/api/v1/users)

## Error Handling
Implement global error handling:

```javascript
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({
        error: 'Something went wrong!',
        message: process.env.NODE_ENV === 'development' ? err.message : undefined
    });
});
```

## Validation
Always validate incoming data:

```javascript
const { body, validationResult } = require('express-validator');

app.post('/api/users',
    body('email').isEmail(),
    body('name').isLength({ min: 3 }),
    (req, res) => {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }
        // Process valid data...
    }
);
```

With these patterns, you''ll build maintainable and scalable APIs!', '00000000-0000-0000-0000-000000000001', 'published', '2024-01-25 09:15:00'),

('30000000-0000-0000-0000-000000000004', 'React Hooks: A Complete Guide', 'react-hooks-complete-guide', 'Master React Hooks and learn how to build modern React applications without classes.', '# React Hooks: A Complete Guide

React Hooks revolutionized how we write React components. Let''s explore the most important hooks and how to use them effectively.

## useState Hook
The `useState` hook lets you add state to functional components.

```javascript
import React, { useState } from 'react';

function Counter() {
    const [count, setCount] = useState(0);
    
    return (
        <div>
            <p>Count: {count}</p>
            <button onClick={() => setCount(count + 1)}>
                Increment
            </button>
        </div>
    );
}
```

## useEffect Hook
Handle side effects in functional components.

```javascript
import React, { useState, useEffect } from 'react';

function UserProfile({ userId }) {
    const [user, setUser] = useState(null);
    
    useEffect(() => {
        fetchUser(userId).then(setUser);
    }, [userId]);
    
    if (!user) return <div>Loading...</div>;
    
    return <div>{user.name}</div>;
}
```

## Custom Hooks
Create reusable stateful logic.

```javascript
function useCounter(initialValue = 0) {
    const [count, setCount] = useState(initialValue);
    
    const increment = () => setCount(count + 1);
    const decrement = () => setCount(count - 1);
    const reset = () => setCount(initialValue);
    
    return { count, increment, decrement, reset };
}
```

## useContext Hook
Share data between components without prop drilling.

```javascript
const ThemeContext = React.createContext('light');

function App() {
    return (
        <ThemeContext.Provider value="dark">
            <Toolbar />
        </ThemeContext.Provider>
    );
}

function Toolbar() {
    const theme = useContext(ThemeContext);
    return <div style={{ background: theme }}>...</div>;
}
```

Hooks make React development more intuitive and functional!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-01 16:45:00'),

('30000000-0000-0000-0000-000000000005', 'Database Design Best Practices', 'database-design-best-practices', 'Learn essential database design principles for building scalable and maintainable applications.', '# Database Design Best Practices

Good database design is crucial for application performance and maintainability. Here are the key principles to follow.

## Normalization
Normalize your data to reduce redundancy:

- **1NF**: Eliminate repeating groups
- **2NF**: Remove partial dependencies
- **3NF**: Remove transitive dependencies

```sql
-- Bad design (not normalized)
CREATE TABLE orders (
    id INT PRIMARY KEY,
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    product1_name VARCHAR(100),
    product1_price DECIMAL(10,2),
    product2_name VARCHAR(100),
    product2_price DECIMAL(10,2)
);

-- Good design (normalized)
CREATE TABLE customers (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE
);

CREATE TABLE products (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2)
);

CREATE TABLE orders (
    id INT PRIMARY KEY,
    customer_id INT,
    order_date DATE,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE order_items (
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

## Indexing Strategy
Create indexes strategically:

```sql
-- Good indexes
CREATE INDEX idx_orders_customer_id ON orders(customer_id);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_products_name ON products(name);

-- Composite index for common queries
CREATE INDEX idx_order_items_order_product ON order_items(order_id, product_id);
```

## Data Types
Choose appropriate data types:

- Use `INT` for IDs, not `VARCHAR`
- Use `DECIMAL` for money, not `FLOAT`
- Use `TEXT` only for large content
- Use `BOOLEAN` for flags

## Naming Conventions
Be consistent with naming:

- Use lowercase with underscores: `user_profiles`
- Use singular for table names: `user`, not `users`
- Use descriptive column names: `created_at`, not `cdate`

Following these practices will save you countless hours of debugging!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-05 11:20:00'),

('30000000-0000-0000-0000-000000000006', 'Vue.js 3 Composition API', 'vuejs-3-composition-api', 'Discover the power of Vue.js 3 Composition API and how it compares to the Options API.', '# Vue.js 3 Composition API

The Composition API in Vue.js 3 provides a more flexible way to organize component logic. Let''s explore its benefits and usage.

## Setup Function
The `setup` function is the entry point for the Composition API.

```javascript
import { ref, computed, onMounted } from 'vue';

export default {
    setup() {
        const count = ref(0);
        const doubled = computed(() => count.value * 2);
        
        const increment = () => {
            count.value++;
        };
        
        onMounted(() => {
            console.log('Component mounted!');
        });
        
        return {
            count,
            doubled,
            increment
        };
    }
}
```

## Reactive References
Use `ref` and `reactive` to create reactive data.

```javascript
import { ref, reactive } from 'vue';

// ref for primitive values
const message = ref('Hello');
console.log(message.value); // Access with .value

// reactive for objects
const user = reactive({
    name: 'John',
    age: 30
});
console.log(user.name); // Direct access
```

## Computed Properties
Create computed values that update automatically.

```javascript
import { ref, computed } from 'vue';

const firstName = ref('John');
const lastName = ref('Doe');

const fullName = computed(() => `${firstName.value} ${lastName.value}`);
```

## Lifecycle Hooks
Use lifecycle hooks inside setup.

```javascript
import { onMounted, onUnmounted, ref } from 'vue';

export default {
    setup() {
        const timer = ref(null);
        
        onMounted(() => {
            timer.value = setInterval(() => {
                console.log('Tick');
            }, 1000);
        });
        
        onUnmounted(() => {
            clearInterval(timer.value);
        });
    }
}
```

## Custom Composables
Create reusable logic with composables.

```javascript
// useCounter.js
import { ref } from 'vue';

export function useCounter(initialValue = 0) {
    const count = ref(initialValue);
    
    const increment = () => count.value++;
    const decrement = () => count.value--;
    const reset = () => count.value = initialValue;
    
    return { count, increment, decrement, reset };
}

// In component
import { useCounter } from './useCounter';

export default {
    setup() {
        const { count, increment, decrement, reset } = useCounter(10);
        
        return { count, increment, decrement, reset };
    }
}
```

The Composition API offers better TypeScript support and code organization!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-10 13:00:00'),

('30000000-0000-0000-0000-000000000007', 'API Security Best Practices', 'api-security-best-practices', 'Essential security measures every developer should implement when building APIs.', '# API Security Best Practices

Security is critical when building APIs. Here are essential practices to protect your application and users.

## Authentication & Authorization
Implement proper authentication and authorization:

```javascript
// JWT Authentication
const jwt = require('jsonwebtoken');

function authenticateToken(req, res, next) {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1];
    
    if (!token) {
        return res.sendStatus(401);
    }
    
    jwt.verify(token, process.env.ACCESS_TOKEN_SECRET, (err, user) => {
        if (err) return res.sendStatus(403);
        req.user = user;
        next();
    });
}
```

## Input Validation
Always validate and sanitize input:

```javascript
const { body, validationResult } = require('express-validator');

app.post('/api/users',
    body('email').isEmail().normalizeEmail(),
    body('password').isLength({ min: 8 }).matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/),
    body('name').trim().escape(),
    (req, res) => {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }
        // Process validated data...
    }
);
```

## Rate Limiting
Prevent abuse with rate limiting:

```javascript
const rateLimit = require('express-rate-limit');

const limiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 minutes
    max: 100, // limit each IP to 100 requests
    message: 'Too many requests from this IP'
});

app.use('/api/', limiter);
```

## HTTPS Only
Always use HTTPS in production:

```javascript
app.use((req, res, next) => {
    if (req.protocol === 'https') {
        next();
    } else {
        res.redirect(301, `https://${req.headers.host}${req.url}`);
    }
});
```

## CORS Configuration
Configure CORS properly:

```javascript
const cors = require('cors');

const corsOptions = {
    origin: ['https://yourdomain.com'],
    credentials: true,
    optionsSuccessStatus: 200
};

app.use(cors(corsOptions));
```

## Security Headers
Add security headers:

```javascript
const helmet = require('helmet');
app.use(helmet());

// Custom headers
app.use((req, res, next) => {
    res.setHeader('X-Content-Type-Options', 'nosniff');
    res.setHeader('X-Frame-Options', 'DENY');
    res.setHeader('X-XSS-Protection', '1; mode=block');
    next();
});
```

Security should be built into your API from the start, not added as an afterthought!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-15 10:30:00'),

('30000000-0000-0000-0000-000000000008', 'Performance Optimization Techniques', 'performance-optimization-techniques', 'Learn how to optimize your web applications for maximum performance.', '# Performance Optimization Techniques

Optimizing web application performance is crucial for user experience. Here are proven techniques to make your apps faster.

## Frontend Optimization
Minimize and optimize frontend assets:

```javascript
// Lazy loading images
const img = document.querySelector('img');
img.loading = 'lazy';

// Code splitting
import('./heavy-module').then(module => {
    module.doSomething();
});

// Debounce expensive operations
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
```

## Database Optimization
Optimize database queries:

```sql
-- Add proper indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_posts_author_date ON posts(author_id, created_at);

-- Use EXPLAIN to analyze queries
EXPLAIN SELECT * FROM posts WHERE author_id = 1 ORDER BY created_at DESC;

-- Avoid N+1 queries with JOINs
SELECT p.*, u.name as author_name 
FROM posts p 
JOIN users u ON p.author_id = u.id 
WHERE p.status = 'published';
```

## Caching Strategies
Implement multi-level caching:

```javascript
// Redis caching example
const redis = require('redis');
const client = redis.createClient();

async function getCachedData(key) {
    const cached = await client.get(key);
    if (cached) {
        return JSON.parse(cached);
    }
    
    const data = await fetchFromDatabase(key);
    await client.setex(key, 3600, JSON.stringify(data));
    return data;
}
```

## HTTP Caching
Leverage browser caching:

```javascript
// Set cache headers
app.use(express.static('public', {
    maxAge: '1y',
    etag: true,
    lastModified: true
}));

// Cache API responses
app.get('/api/posts', async (req, res) => {
    const posts = await getPosts();
    
    res.set({
        'Cache-Control': 'public, max-age=300',
        'ETag': generateETag(posts)
    });
    
    if (req.headers['if-none-match'] === res.get('ETag')) {
        return res.status(304).end();
    }
    
    res.json(posts);
});
```

## Bundle Optimization
Optimize JavaScript bundles:

```javascript
// webpack.config.js
module.exports = {
    optimization: {
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
            },
        },
    },
};
```

Performance optimization is an ongoing process that requires continuous monitoring and improvement!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-20 15:00:00'),

('30000000-0000-0000-0000-000000000009', 'Testing Strategies for Modern Apps', 'testing-strategies-modern-apps', 'Comprehensive guide to testing modern web applications with unit, integration, and E2E tests.', '# Testing Strategies for Modern Apps

Testing is essential for maintaining code quality. Let''s explore different testing strategies and when to use them.

## Unit Testing
Test individual functions and components in isolation:

```javascript
// Jest unit test example
describe('Calculator', () => {
    test('should add two numbers correctly', () => {
        const result = add(2, 3);
        expect(result).toBe(5);
    });
    
    test('should handle negative numbers', () => {
        const result = add(-2, 3);
        expect(result).toBe(1);
    });
});

// React component test
import { render, screen, fireEvent } from '@testing-library/react';
import Button from './Button';

test('should call onClick when clicked', () => {
    const handleClick = jest.fn();
    render(<Button onClick={handleClick}>Click me</Button>);
    
    fireEvent.click(screen.getByText('Click me'));
    expect(handleClick).toHaveBeenCalledTimes(1);
});
```

## Integration Testing
Test how different parts work together:

```javascript
// API integration test
describe('User API', () => {
    test('should create and retrieve user', async () => {
        const userData = {
            name: 'John Doe',
            email: 'john@example.com'
        };
        
        // Create user
        const createResponse = await request(app)
            .post('/api/users')
            .send(userData)
            .expect(201);
        
        const userId = createResponse.body.id;
        
        // Retrieve user
        const getResponse = await request(app)
            .get(`/api/users/${userId}`)
            .expect(200);
        
        expect(getResponse.body.name).toBe(userData.name);
        expect(getResponse.body.email).toBe(userData.email);
    });
});
```

## End-to-End Testing
Test complete user workflows:

```javascript
// Cypress E2E test
describe('User Registration', () => {
    it('should register a new user', () => {
        cy.visit('/register');
        
        cy.get('[data-testid=name-input]').type('John Doe');
        cy.get('[data-testid=email-input]').type('john@example.com');
        cy.get('[data-testid=password-input]').type('password123');
        cy.get('[data-testid=submit-button]').click();
        
        cy.url().should('include', '/dashboard');
        cy.get('[data-testid=welcome-message]').should('contain', 'John Doe');
    });
});
```

## Test Pyramid
Follow the test pyramid principle:

- **70% Unit Tests**: Fast, isolated tests
- **20% Integration Tests**: Test component interactions
- **10% E2E Tests**: Test complete user journeys

## Mocking and Stubbing
Use mocks to isolate dependencies:

```javascript
// Mocking external API
jest.mock('./apiService');
import { fetchUser } from './apiService';

test('should display user data', async () => {
    fetchUser.mockResolvedValue({
        id: 1,
        name: 'John Doe',
        email: 'john@example.com'
    });
    
    const component = render(<UserProfile userId={1} />);
    await waitFor(() => {
        expect(component.getByText('John Doe')).toBeInTheDocument();
    });
});
```

## Test Coverage
Aim for high test coverage, but focus on critical paths:

```javascript
// Generate coverage report
"scripts": {
    "test": "jest",
    "test:coverage": "jest --coverage",
    "test:watch": "jest --watch"
}
```

Good testing practices catch bugs early and make refactoring safer!', '00000000-0000-0000-0000-000000000001', 'published', '2024-02-25 12:15:00'),

('30000000-0000-0000-0000-000000000010', 'Microservices Architecture Guide', 'microservices-architecture-guide', 'Learn how to design, build, and deploy microservices for scalable applications.', '# Microservices Architecture Guide

Microservices architecture offers scalability and flexibility for complex applications. Here''s how to get started.

## What Are Microservices?
Microservices are small, independent services that communicate through APIs:

```javascript
// User Service
const express = require('express');
const app = express();

app.get('/users/:id', async (req, res) => {
    const user = await User.findById(req.params.id);
    res.json(user);
});

app.post('/users', async (req, res) => {
    const user = await User.create(req.body);
    res.status(201).json(user);
});

app.listen(3001, () => {
    console.log('User service running on port 3001');
});
```

## Service Communication
Services communicate through REST APIs or message queues:

```javascript
// Service-to-service communication
const axios = require('axios');

class OrderService {
    async createOrder(orderData) {
        // Validate user exists
        const user = await axios.get(`http://user-service:3001/users/${orderData.userId}`);
        
        // Check inventory
        const inventory = await axios.get(`http://inventory-service:3002/products/${orderData.productId}`);
        
        if (inventory.data.stock < orderData.quantity) {
            throw new Error('Insufficient stock');
        }
        
        // Create order
        const order = await Order.create(orderData);
        
        // Publish event
        await this.publishEvent('order.created', order);
        
        return order;
    }
}
```

## API Gateway
Use an API gateway to handle routing and authentication:

```javascript
// API Gateway with Express Gateway
const eg = require('express-gateway');

eg.run({
    config: {
        http: {
            port: 8080
        },
        apiEndpoints: {
            users: {
                host: '*',
                paths: '/api/users/*'
            },
            orders: {
                host: '*',
                paths: '/api/orders/*'
            }
        },
        serviceEndpoints: {
            userService: {
                url: 'http://user-service:3001'
            },
            orderService: {
                url: 'http://order-service:3003'
            }
        },
        policies: ['jwt', 'proxy'],
        pipelines: {
            users: {
                apiEndpoints: ['users'],
                policies: [
                    { jwt: {} },
                    { proxy: { serviceEndpoint: 'userService' } }
                ]
            }
        }
    }
});
```

## Data Management
Each service manages its own database:

```javascript
// Each service has its own database connection
class UserService {
    constructor() {
        this.db = new Database('users_db');
    }
}

class OrderService {
    constructor() {
        this.db = new Database('orders_db');
    }
}
```

## Containerization
Containerize services with Docker:

```dockerfile
# Dockerfile for user service
FROM node:16-alpine
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production
COPY . .
EXPOSE 3001
CMD ["node", "src/index.js"]
```

```yaml
# docker-compose.yml
version: '3.8'
services:
  user-service:
    build: ./user-service
    ports:
      - "3001:3001"
    environment:
      - DB_HOST=user-db
    depends_on:
      - user-db
  
  user-db:
    image: postgres:13
    environment:
      POSTGRES_DB: users
      POSTGRES_USER: user
      POSTGRES_PASSWORD: password
```

## Service Discovery
Implement service discovery for dynamic routing:

```javascript
// Consul service discovery
const consul = require('consul')();

class ServiceRegistry {
    async registerService(name, id, port) {
        await consul.agent.service.register({
            name: name,
            id: id,
            port: port,
            check: {
                http: `http://localhost:${port}/health`,
                interval: '10s'
            }
        });
    }
    
    async discoverService(name) {
        const services = await consul.health.service(name);
        return services.map(service => ({
            host: service.Service.Address,
            port: service.Service.Port
        }));
    }
}
```

Microservices offer great benefits but require careful planning and infrastructure!', '00000000-0000-0000-0000-000000000001', 'published', '2024-03-01 14:00:00');

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
