<?php
// config/pages.php - ROZŠÍRENÁ VERZIA
return [
    // === PUBLIC ROUTES ===
    'home' => [
        'title' => 'ChubbyBlog - Modern PHP Blog',
        'description' => 'Modern blog built with DDD + Hexagonal Architecture',
        'template' => 'boson::app/home',
    ],
    'about' => [
        'title' => 'O projekte - ChubbyBlog',
        'description' => 'Informácie o architektúre a technológiách použitých v ChubbyBlog',
        'template' => 'boson::app/about',
    ],
    'blog.index' => [
        'title' => 'Blog - ChubbyBlog',
        'description' => 'Read our latest articles about programming, architecture and software development',
        'template' => 'boson::modules/blog/index',
    ],
    'blog.show' => [
        'title' => '%s - ChubbyBlog',
        'description' => '%s',
        'template' => 'boson::modules/blog/article-show',
    ],

    // === AUTH ROUTES ===
    'auth.login' => [
        'title' => 'Login - ChubbyBlog',
        'description' => 'Login to your ChubbyBlog account',
        'template' => 'boson::auth/login',
    ],
    'auth.register' => [
        'title' => 'Register - ChubbyBlog',
        'description' => 'Create a new ChubbyBlog account',
        'template' => 'boson::auth/register',
    ],

    // === ARTICLE ROUTES ===
    'article.create' => [
        'title' => 'Create Article - ChubbyBlog',
        'description' => 'Create a new article',
        'template' => 'boson::article/create',
    ],
    'article.edit' => [
        'title' => 'Edit Article - ChubbyBlog',
        'description' => 'Edit article',
        'template' => 'boson::article/edit',
    ],

    // === MARK ROUTES ===
    'mark.dashboard' => [
        'title' => 'Mark Dashboard - ChubbyBlog',
        'description' => 'Mark dashboard for managing blog content',
        'template' => 'boson::mark/dashboard',
    ],
    'mark.articles.index' => [
        'title' => 'Manage Articles - ChubbyBlog Mark',
        'description' => 'Manage blog articles in the Mark panel',
        'template' => 'boson::mark/articles/index',
    ],
    'mark.articles.show' => [
        'title' => '%s - Edit Article',
        'description' => 'Edit article details',
        'template' => 'boson::mark/articles/show',
    ],
];
