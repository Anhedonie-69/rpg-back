<?php
namespace App\Data;

class DialogueData
{
    public static function get(string $id): array
    {
        $dialogues = [
            'dialogue_vieux_sage' => [
                'id'      => 'dialogue_vieux_sage',
                'speaker' => 'Vieux Sage',
                'nodes'   => [
                    'start' => [
                        'text'    => 'Bienvenue, jeune voyageur. Ces terres sont dangereuses.',
                        'choices' => [
                            ['text' => 'Parle-moi de ces dangers.', 'next' => 'dangers'],
                            ['text' => 'As-tu quelque chose à vendre ?', 'next' => 'shop'],
                            ['text' => 'Au revoir.', 'next' => 'end'],
                        ]
                    ],
                    'dangers' => [
                        'text'    => 'Des créatures rôdent dans la forêt à l\'est. Sois prudent.',
                        'choices' => [
                            ['text' => 'Merci du conseil.', 'next' => 'start'],
                            ['text' => 'Au revoir.', 'next' => 'end'],
                        ]
                    ],
                    'shop' => [
                        'text'    => 'Je n\'ai rien à vendre, mais je peux te donner un conseil gratuit.',
                        'choices' => [
                            ['text' => 'Quel conseil ?', 'next' => 'dangers'],
                            ['text' => 'Non merci.', 'next' => 'end'],
                        ]
                    ],
                    'end' => [
                        'text'    => 'Bonne chance, voyageur.',
                        'choices' => []  // ← vide = ferme le dialogue
                    ],
                ]
            ],
        ];

        if (!isset($dialogues[$id])) {
            throw new \InvalidArgumentException("Dialogue inconnu : $id");
        }

        return $dialogues[$id];
    }
}