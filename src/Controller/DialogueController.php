<?php
namespace App\Controller;

use App\Data\DialogueData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DialogueController extends AbstractController
{
    #[Route('/api/dialogue/{id}', name: 'api_dialogue_get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        try {
            $dialogue = DialogueData::get($id);
            return $this->json($dialogue);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }
}