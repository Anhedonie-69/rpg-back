<?php
namespace App\Data;

class EnemyData
{
    public static function get(string $id): array
    {
        $enemies = [
            'slime' => [
                'id'     => 'slime',
                'name'   => 'Slime',
                'hp'     => 80,
                'mp'     => 0,
                'atk'    => 8,
                'def'    => 4,
                'mag'    => 2,
                'res'    => 2,
                'spd'    => 6,
                'lck'    => 2,
                'xp'     => 10,
                'gold'   => 5,
                'skills' => ['attack'],
                'color'  => 0x00ff00,
                'size'   => 48,
            ],
            'goblin' => [
                'id'     => 'goblin',
                'name'   => 'Gobelin',
                'hp'     => 120,
                'mp'     => 10,
                'atk'    => 12,
                'def'    => 6,
                'mag'    => 4,
                'res'    => 3,
                'spd'    => 10,
                'lck'    => 4,
                'xp'     => 20,
                'gold'   => 10,
                'skills' => ['attack'],
                'color'  => 0xff6600,
                'size'   => 56,
            ],
            'dark_knight' => [
                'id'     => 'dark_knight',
                'name'   => 'Chevalier Noir',
                'hp'     => 300,
                'mp'     => 50,
                'atk'    => 25,
                'def'    => 20,
                'mag'    => 10,
                'res'    => 15,
                'spd'    => 8,
                'lck'    => 6,
                'xp'     => 100,
                'gold'   => 50,
                'skills' => ['attack', 'dark_slash'],
                'color'  => 0x330033,
                'size'   => 72,
            ],
        ];

        if (!isset($enemies[$id])) {
            throw new \InvalidArgumentException("Ennemi inconnu : $id");
        }

        return $enemies[$id];
    }

    // Groupe d'ennemis aléatoire selon la zone
    public static function getGroupForZone(string $zone): array
    {
        $groups = [
            'default'  => [
                ['slime', 'slime'],
                ['slime', 'goblin'],
                ['goblin'],
            ],
            'foret'    => [
                ['goblin', 'goblin'],
                ['goblin', 'slime', 'slime'],
            ],
            'donjon'   => [
                ['dark_knight'],
                ['goblin', 'dark_knight'],
            ],
        ];

        $zoneGroups = $groups[$zone] ?? $groups['default'];
        $group      = $zoneGroups[array_rand($zoneGroups)];

        return array_map(fn($id) => self::get($id), $group);
    }
}