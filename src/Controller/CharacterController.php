<?php
namespace App\Controller;

use App\Entity\Character;
use App\DataFixtures\CharacterStats;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    // GET /api/characters → récupère l'équipe du joueur
    #[Route('/api/characters', name: 'api_characters_list', methods: ['GET'])]
    public function list(CharacterRepository $repo): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $characters = $repo->findBy(['user' => $user], ['slot' => 'ASC']);

        return $this->json(array_map(fn($c) => $this->serialize($c), $characters));
    }

    // POST /api/characters/init → initialise les 4 personnages à la création de compte
    #[Route('/api/characters/init', name: 'api_characters_init', methods: ['POST'])]
    public function init(EntityManagerInterface $em, CharacterRepository $repo): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Vérifie si déjà initialisé
        if ($repo->count(['user' => $user]) > 0) {
            return $this->json(['error' => 'Personnages déjà initialisés'], 409);
        }

        $classes = ['monk', 'warrior', 'red_mage', 'alchemist'];

        foreach ($classes as $slot => $class) {
            $stats = CharacterStats::getBaseStats($class);

            $character = new Character();
            $character->setUser($user);
            $character->setClass($class);
            $character->setSlot($slot + 1);
            $character->setName($stats['name']);
            $character->setHpMax($stats['hpMax']);
            $character->setMpMax($stats['mpMax']);
            $character->setHpCurrent($stats['hpMax']);
            $character->setMpCurrent($stats['mpMax']);
            $character->setAtk($stats['atk']);
            $character->setDef($stats['def']);
            $character->setMag($stats['mag']);
            $character->setRes($stats['res']);
            $character->setSpd($stats['spd']);
            $character->setLck($stats['lck']);
            $character->setUnlocked($stats['unlocked']);
            $character->setSkills($stats['skills']);

            $em->persist($character);
        }

        $em->flush();

        return $this->json(['message' => '4 personnages créés avec succès'], 201);
    }

    private function serialize(Character $c): array
    {
        return [
            'id'            => $c->getId(),
            'name'          => $c->getName(),
            'class'         => $c->getClass(),
            'slot'          => $c->getSlot(),
            'unlocked'      => $c->isUnlocked(),
            'level'         => $c->getLevel(),
            'experience'    => $c->getExperience(),
            'hpMax'         => $c->getHpMax(),
            'mpMax'         => $c->getMpMax(),
            'hpCurrent'     => $c->getHpCurrent(),
            'mpCurrent'     => $c->getMpCurrent(),
            'atk'           => $c->getAtk(),
            'def'           => $c->getDef(),
            'mag'           => $c->getMag(),
            'res'           => $c->getRes(),
            'spd'           => $c->getSpd(),
            'lck'           => $c->getLck(),
            'statusEffects' => $c->getStatusEffects(),
            'skills'        => $c->getSkills(),
        ];
    }
}