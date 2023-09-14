<?php

namespace App\Controller;

use App\Request\MessageRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Finder\Finder;
use App\Files\MessageFilesOperation;
use Throwable;

#[Route('/api', name: 'api_')]
class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageFilesOperation $filesOperation
    ) {
    }

    #[Route('/messages', name: 'messages_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] MessageRequest $messageRequest): JsonResponse
    {
        $uuid = $this->filesOperation->dumpFile($messageRequest->message);

        return $this->json([
            'id' => $uuid
        ]);
    }

    #[Route('/messages', name: 'messages_get_all', methods: ['get'])]
    public function getAll(Request $request): JsonResponse
    {
        $sort = $request->query->get('sort') ?? '';
        $sort = json_decode($sort, true);
        $files = $this->filesOperation->findFiles($this->getParameter('path_to_dump_file'), $sort[0] ?? []);

        return $this->json($files);
    }

    #[Route('/messages/{id}', name: 'messages_get', methods: ['get'])]
    public function get(string $id): JsonResponse
    {
        $files = $this->filesOperation->findFile($this->getParameter('path_to_dump_file'), $id);

        return $this->json($files);
    }
}