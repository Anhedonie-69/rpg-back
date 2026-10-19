<?php
namespace App\Controller;

use App\Data\EnemyData;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Data\ItemData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BattleController extends AbstractController
{
    // GET /api/battle/enemies?zone=default → groupe d'ennemis
    #[Route('/api/battle/enemies', name: 'api_battle_enemies', methods: ['GET'])]
    public function getEnemies(Request $request): JsonResponse
    {
        $zone    = $request->query->get('zone', 'default');
        $enemies = EnemyData::getGroupForZone($zone);
        return $this->json($enemies);
    }

    // POST /api/battle/loot → calcule le loot après victoire
    #[Route('/api/battle/loot', name: 'api_battle_loot', methods: ['POST'])]
    public function loot(Request $request): JsonResponse
    {
        $data    = json_decode($request->getContent(), true);
        $enemies = $data['enemies']; // tableau des ennemis vaincus
    
        $totalXp   = 0;
        $totalGold = 0;
        $items     = [];
    
        foreach ($enemies as $enemy) {
            $enemyData  = EnemyData::get($enemy['id']);
            $totalXp   += $enemyData['xp'];
            $totalGold += $enemyData['gold'];
    
            // Calcule le loot
            foreach ($enemyData['loot'] ?? [] as $lootEntry) {
                if (rand(1, 100) <= $lootEntry['chance']) {
                    $item    = ItemData::get($lootEntry['id']);
                    $items[] = array_merge($item, ['quantity' => $lootEntry['quantity']]);
                }
            }
        }
    
        return $this->json([
            'xp'    => $totalXp,
            'gold'  => $totalGold,
            'items' => $items,
        ]);
    }

    // POST /api/battle/damage → calcule les dégâts
    #[Route('/api/battle/damage', name: 'api_battle_damage', methods: ['POST'])]
    public function calculateDamage(Request $request, CharacterRepository $repo): JsonResponse
    {
        $data       = json_decode($request->getContent(), true);
        $attackerId = $data['attackerId'];  // id du character ou de l'ennemi
        $targetId   = $data['targetId'];
        $skillId    = $data['skillId'] ?? 'attack';
        $attackerType = $data['attackerType']; // 'character' ou 'enemy'
        $targetType   = $data['targetType'];

        // Récupère les stats de l'attaquant
        if ($attackerType === 'character') {
            /** @var \App\Entity\User $user */
            $user      = $this->getUser();
            $attacker  = $repo->findOneBy(['id' => $attackerId, 'user' => $user]);
            if (!$attacker) {
                return $this->json(['error' => 'Personnage introuvable'], 404);
            };
            $atkStat = $attacker->getAtk();
            $magStat = $attacker->getMag();
            $lckStat = $attacker->getLck();
        } else {
            // Ennemi
            $enemyData = EnemyData::get($attackerId);
            $atkStat   = $enemyData['atk'];
            $magStat   = $enemyData['mag'];
            $lckStat   = $enemyData['lck'];
        };

        // Récupère la défense de la cible
        if ($targetType === 'character') {
            /** @var \App\Entity\User $user */
            $user   = $this->getUser();
            $target = $repo->findOneBy(['id' => $targetId, 'user' => $user]);
            if (!$target) {
                return $this->json(['error' => 'Cible introuvable'], 404);
            };
            $defStat = $target->getDef();
            $resStat = $target->getRes();
        } else {
            $enemyData = EnemyData::get($targetId);
            $defStat   = $enemyData['def'];
            $resStat   = $enemyData['res'];
        };

        // Calcul des dégâts selon le skill
        $damage = $this->computeDamage($skillId, $atkStat, $magStat, $defStat, $resStat, $lckStat);

        return $this->json([
            'damage'   => $damage['amount'],
            'isCrit'   => $damage['isCrit'],
            'skillId'  => $skillId,
        ]);
    }

    private function computeDamage(
        string $skillId,
        int $atk,
        int $mag,
        int $def,
        int $res,
        int $lck
    ): array {
        // Critique ?
        $isCrit    = rand(1, 100) <= $lck;
        $critMult  = $isCrit ? 1.5 : 1.0;

        $amount = match($skillId) {
            'attack'     => max(1, (int)(($atk * 2 - $def) * $critMult * $this->randomVariance())),
            'kick'       => max(1, (int)(($atk * 1.5 - $def * 0.8) * $critMult * $this->randomVariance())),
            'fire'       => max(1, (int)(($mag * 2.5 - $res) * $critMult * $this->randomVariance())),
            'dark_slash' => max(1, (int)(($atk * 3 - $def * 0.5) * $critMult * $this->randomVariance())),
            default      => max(1, (int)(($atk - $def) * $critMult * $this->randomVariance())),
        };

        return ['amount' => $amount, 'isCrit' => $isCrit];
    }

    private function randomVariance(): float
    {
        // Variance de ±10%
        return rand(90, 110) / 100;
    }
}