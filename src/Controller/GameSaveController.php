<?php
namespace App\Controller;

use App\Entity\GameSave;
use App\Repository\GameSaveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameSaveController extends AbstractController
{
    // GET /api/saves → liste les 3 slots
    #[Route('/api/saves', name: 'api_saves_list', methods: ['GET'])]
    public function list(GameSaveRepository $repo): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $saves = $repo->findBy(['user' => $user], ['slot' => 'ASC']);

        $result = [];
        foreach ([1, 2, 3] as $slot) {
            $save = array_filter($saves, fn($s) => $s->getSlot() === $slot);
            $save = array_values($save)[0] ?? null;

            $result[] = $save ? [
                'slot'      => $save->getSlot(),
                'mapId'     => $save->getMapId(),
                'posX'      => $save->getPosX(),
                'posY'      => $save->getPosY(),
                'playTime'  => $save->getPlayTime(),
                'gold'      => $save->getGold(),
                'chapter'   => $save->getChapter(),
                'flags'     => $save->getFlags(),
                'partyIds'  => $save->getPartyIds(),
                'updatedAt' => $save->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ] : [
                'slot'  => $slot,
                'empty' => true,
            ];
        }

        return $this->json($result);
    }

    // POST /api/saves/{slot} → crée ou écrase une save
    #[Route('/api/saves/{slot}', name: 'api_saves_save', methods: ['POST'], requirements: ['slot' => '[1-3]'])]
    public function save(int $slot, Request $request, EntityManagerInterface $em, GameSaveRepository $repo): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        // Récupère la save existante ou en crée une nouvelle
        $save = $repo->findOneBy(['user' => $user, 'slot' => $slot]);
        if (!$save) {
            $save = new GameSave();
            $save->setUser($user);
            $save->setSlot($slot);
            $em->persist($save);
        }

        if (isset($data['mapId']))    $save->setMapId($data['mapId']);
        if (isset($data['posX']))     $save->setPosX((int) $data['posX']);
        if (isset($data['posY']))     $save->setPosY((int) $data['posY']);
        if (isset($data['playTime'])) $save->setPlayTime((int) $data['playTime']);
        if (isset($data['gold']))     $save->setGold((int) $data['gold']);
        if (isset($data['chapter']))  $save->setChapter((int) $data['chapter']);
        if (isset($data['flags']))    $save->setFlags($data['flags']);
        if (isset($data['partyIds'])) $save->setPartyIds($data['partyIds']);

        $em->flush();

        return $this->json([
            'message' => "Slot $slot sauvegardé avec succès",
            'slot'    => $slot,
        ]);
    }

    // DELETE /api/saves/{slot} → supprime une save
    #[Route('/api/saves/{slot}', name: 'api_saves_delete', methods: ['DELETE'], requirements: ['slot' => '[1-3]'])]
    public function delete(int $slot, EntityManagerInterface $em, GameSaveRepository $repo): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $save = $repo->findOneBy(['user' => $user, 'slot' => $slot]);

        if (!$save) {
            return $this->json(['error' => 'Aucune sauvegarde sur ce slot'], 404);
        }

        $em->remove($save);
        $em->flush();

        return $this->json(['message' => "Slot $slot supprimé avec succès"]);
    }
}