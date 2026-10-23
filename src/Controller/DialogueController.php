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
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $lang = $user->getLanguage() ?? 'fr';
    
            $dialogue = DialogueData::get($id, $lang);
            return $this->json($dialogue);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }
}