<?php
namespace App\DataFixtures;

class CharacterStats
{
    public static function getBaseStats(string $class): array
    {
        return match($class) {
            'monk' => [
                'name'      => 'Moine',
                'hpMax'     => 150,
                'mpMax'     => 30,
                'atk'       => 15,
                'def'       => 12,
                'mag'       => 5,
                'res'       => 8,
                'spd'       => 12,
                'lck'       => 8,
                'unlocked'  => true,  // disponible dès le début
                'skills'    => ['attack', 'kick', 'meditate'],
            ],
            'warrior' => [
                'name'      => 'Guerrier',
                'hpMax'     => 180,
                'mpMax'     => 20,
                'atk'       => 18,
                'def'       => 16,
                'mag'       => 3,
                'res'       => 6,
                'spd'       => 8,
                'lck'       => 6,
                'unlocked'  => false,
                'skills'    => ['attack', 'shield_bash', 'provoke'],
            ],
            'red_mage' => [
                'name'      => 'Mage Rouge',
                'hpMax'     => 110,
                'mpMax'     => 80,
                'atk'       => 10,
                'def'       => 8,
                'mag'       => 16,
                'res'       => 14,
                'spd'       => 10,
                'lck'       => 10,
                'unlocked'  => false,
                'skills'    => ['attack', 'fire', 'cure'],
            ],
            'alchemist' => [
                'name'      => 'Alchimiste',
                'hpMax'     => 100,
                'mpMax'     => 60,
                'atk'       => 8,
                'def'       => 7,
                'mag'       => 12,
                'res'       => 10,
                'spd'       => 14,
                'lck'       => 15,
                'unlocked'  => false,
                'skills'    => ['attack', 'potion', 'transmute'],
            ],
            default => throw new \InvalidArgumentException("Classe inconnue : $class")
        };
    }
}
