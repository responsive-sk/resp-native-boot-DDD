<?php
// config/pages.php - ROZŠÍRENÁ VERZIA
return [
    // === PUBLIC ROUTES ===
    'home' => [
        'title' => 'ChubbyBlog - Modern PHP Blog',
        'description' => 'Modern blog built with DDD + Hexagonal Architecture',
        'template' => 'resp-front::app/home',
    ],
    'about' => [
        'title' => 'O projekte - ChubbyBlog',
        'description' => 'Informácie o architektúre a technológiách použitých v ChubbyBlog',
        'template' => 'resp-front::app/about',
    ],
    'contact' => [
        'title' => 'Contact Us - ChubbyBlog',
        'description' => 'Get in touch with the ChubbyBlog team.',
        'template' => 'resp-front::app/contact',
    ],
    'blog.index' => [
        'title' => 'Blog - ChubbyBlog',
        'description' => 'Read our latest articles about programming, architecture and software development',
        'template' => 'resp-front::modules/blog/index',
    ],
    'blog.show' => [
        'title' => '%s - ChubbyBlog',
        'description' => '%s',
        'template' => 'resp-front::modules/blog/article-show',
    ],
    'search.index' => [
        'title' => 'Search - ChubbyBlog',
        'description' => 'Search through blog articles',
        'template' => 'resp-front::modules/search/index',
    ],

    // === AUTH ROUTES ===
    'auth.login' => [
        'title' => 'Login - ChubbyBlog',
        'description' => 'Login to your ChubbyBlog account',
        'template' => 'resp-front::auth/login',
    ],
    'auth.register' => [
        'title' => 'Register - ChubbyBlog',
        'description' => 'Create a new ChubbyBlog account',
        'template' => 'resp-front::auth/register',
    ],

    // === ARTICLE ROUTES ===
    'article.create' => [
        'title' => 'Create Article - ChubbyBlog',
        'description' => 'Create a new article',
        'template' => 'resp-front::article/create',
    ],
    'article.edit' => [
        'title' => 'Edit Article - ChubbyBlog',
        'description' => 'Edit article',
        'template' => 'resp-front::article/edit',
    ],

    // === MARK ROUTES ===
    'mark.dashboard' => [
        'title' => 'Mark Dashboard - ChubbyBlog',
        'description' => 'Mark dashboard for managing blog content',
        'template' => 'resp-front::mark/dashboard',
    ],
    'mark.articles.index' => [
        'title' => 'Manage Articles - ChubbyBlog Mark',
        'description' => 'Manage blog articles in the Mark panel',
        'template' => 'resp-front::mark/articles/index',
    ],
    'mark.articles.show' => [
        'title' => '%s - Article Details',
        'description' => 'View article details',
        'template' => 'resp-front::mark/articles/show',
    ],
    'mark.articles.create' => [
        'title' => 'Create Article - Mark',
        'description' => 'Create a new article',
        'template' => 'resp-front::mark/articles/create',
    ],
    'mark.articles.edit' => [
        'title' => 'Edit Article - Mark',
        'description' => 'Edit article',
        'template' => 'resp-front::mark/articles/edit',
    ],
    'mark.users.index' => [
        'title' => 'Manage Users - ChubbyBlog Mark',
        'description' => 'Manage system users',
        'template' => 'resp-front::mark/users/index',
    ],
    'mark.users.create' => [
        'title' => 'Create User - ChubbyBlog Mark',
        'description' => 'Create a new user',
        'template' => 'resp-front::mark/users/create',
    ],
    'mark.users.edit' => [
        'title' => 'Edit User - ChubbyBlog Mark',
        'description' => 'Edit user details',
        'template' => 'resp-front::mark/users/edit',
    ],
];
