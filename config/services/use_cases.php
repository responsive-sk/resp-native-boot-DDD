<?php

declare(strict_types=1);

use Blog\Application\Audit\AuditLogger;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\SearchArticles;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Psr\Container\ContainerInterface;

return [
    AuditLogger::class => fn(ContainerInterface $c) => new AuditLogger(
        $c->get(AuditLogRepository::class)
    ),

    CreateArticle::class => fn(ContainerInterface $c) => new CreateArticle(
        $c->get(ArticleRepository::class)
    ),

    GetAllArticles::class => fn(ContainerInterface $c) => new GetAllArticles(
        $c->get(ArticleRepository::class)
    ),

    UpdateArticle::class => fn(ContainerInterface $c) => new UpdateArticle(
        $c->get(ArticleRepository::class)
    ),

    DeleteArticle::class => fn(ContainerInterface $c) => new DeleteArticle(
        $c->get(ArticleRepository::class)
    ),

    LoginUser::class => fn(ContainerInterface $c) => new LoginUser(
        $c->get(UserRepositoryInterface::class)
    ),

    RegisterUser::class => fn(ContainerInterface $c) => new RegisterUser(
        $c->get(UserRepositoryInterface::class),
        require __DIR__ . '/../password_strength.php'
    ),

    SearchArticles::class => fn(ContainerInterface $c) => new SearchArticles(
        $c->get(ArticleRepository::class)
    ),

    \Blog\Application\Blog\GetArticleBySlug::class => fn(ContainerInterface $c) => new \Blog\Application\Blog\GetArticleBySlug(
        $c->get(ArticleRepository::class)
    ),

    \Blog\Application\Blog\GetAllTags::class => fn(ContainerInterface $c) => new \Blog\Application\Blog\GetAllTags(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class)
    ),

    \Blog\Application\Blog\GetOrCreateTag::class => fn(ContainerInterface $c) => new \Blog\Application\Blog\GetOrCreateTag(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class)
    ),

    \Blog\Application\Blog\ManageArticleTags::class => fn(ContainerInterface $c) => new \Blog\Application\Blog\ManageArticleTags(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class),
        $c->get(\Blog\Application\Blog\GetOrCreateTag::class)
    ),

    \Blog\Application\Form\CreateForm::class => fn(ContainerInterface $c) => new \Blog\Application\Form\CreateForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),

    \Blog\Application\Form\GetForm::class => fn(ContainerInterface $c) => new \Blog\Application\Form\GetForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),
];
