<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Form;

use Blog\Application\Form\CreateForm;
use Blog\Application\Form\GetForm;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class FormController
{
    public function __construct(
        private CreateForm $createForm,
        private GetForm $getForm
    ) {
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // In a real implementation, you would get data from the request body,
        // validate it, and then pass it to the application service.
        $data = json_decode((string) $request->getBody(), true);

        // Basic validation
        if (!isset($data['title']) || !isset($data['slug'])) {
            return new Response(400, ['Content-Type' => 'application/json'], json_encode(['error' => 'Missing title or slug']));
        }

        $form = $this->createForm->execute(
            $data['title'],
            $data['slug'],
            $data['fields'] ?? []
        );

        // Return a 201 Created response
        return new Response(201, ['Content-Type' => 'application/json'], json_encode(['id' => $form->id()->toString()]));
    }

    public function get(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        $form = $this->getForm->bySlug($slug);

        if ($form === null) {
            return new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Form not found']));
        }

        $responseData = [
            'id' => $form->id()->toString(),
            'title' => $form->title(),
            'slug' => $form->slug(),
            'fields' => $form->fields(),
            'created_at' => $form->createdAt()->format(DATE_ATOM),
        ];

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($responseData));
    }
}
