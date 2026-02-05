<?php

declare(strict_types=1);

namespace Blog\Core;

/**
 * Application namespace for all use cases
 */
final class Application
{
    // Blog use cases
    public const BLOG_CREATE_ARTICLE = 'Blog\\Application\\Blog\\CreateArticle';
    public const BLOG_UPDATE_ARTICLE = 'Blog\\Application\\Blog\\UpdateArticle';
    public const BLOG_DELETE_ARTICLE = 'Blog\\Application\\Blog\\DeleteArticle';
    public const BLOG_GET_ALL_ARTICLES = 'Blog\\Application\\Blog\\GetAllArticles';
    public const BLOG_GET_ARTICLE_BY_SLUG = 'Blog\\Application\\Blog\\GetArticleBySlug';
    public const BLOG_SEARCH_ARTICLES = 'Blog\\Application\\Blog\\SearchArticles';
    
    // User use cases
    public const USER_LOGIN_USER = 'Blog\\Application\\User\\LoginUser';
    public const USER_REGISTER_USER = 'Blog\\Application\\User\\RegisterUser';
    public const USER_UPDATE_USER_ROLE = 'Blog\\Application\\User\\UpdateUserRole';
    
    // Image use cases
    public const IMAGE_UPLOAD_IMAGE = 'Blog\\Application\\Image\\UploadImage';
    public const IMAGE_DELETE_IMAGE = 'Blog\\Application\\Image\\DeleteImage';
    public const IMAGE_ATTACH_IMAGE_TO_ARTICLE = 'Blog\\Application\\Image\\AttachImageToArticle';
    
    // Form use cases
    public const FORM_CREATE_FORM = 'Blog\\Application\\Form\\CreateForm';
    public const FORM_GET_FORM = 'Blog\\Application\\Form\\GetForm';
    
    // Audit use cases
    public const AUDIT_LOGGER = 'Blog\\Application\\Audit\\AuditLogger';
}
