<?php
namespace App\Data;

class ItemData
{
    public static function get(string $id): array
    {
        $items = [
            'potion' => [
                'id'          => 'potion',
                'name'        => 'Potion',
                'description' => 'Restaure 50 HP à un personnage.',
                'effect'      => 'heal',
                'value'       => 50,
                'target'      => 'ally',
            ],
        ];

        if (!isset($items[$id])) {
            throw new \InvalidArgumentException("Objet inconnu : $id");
        }

        return $items[$id];
    }
}