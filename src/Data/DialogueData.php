<?php
namespace App\Data;

class DialogueData
{
    public static function get(string $id, string $lang = 'fr'): array
    {
        $dialogues = [
            'dialogue_vieux_sage' => [
                'fr' => [
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
                            'text'    => 'Des créatures rôdent dans la forêt à l\'ouest. Sois prudent.',
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
                            'choices' => []
                        ],
                    ]
                ],
                'en' => [
                    'id'      => 'dialogue_vieux_sage',
                    'speaker' => 'Old Sage',
                    'nodes'   => [
                        'start' => [
                            'text'    => 'Welcome, young traveler. These lands are dangerous.',
                            'choices' => [
                                ['text' => 'Tell me about these dangers.', 'next' => 'dangers'],
                                ['text' => 'Do you have anything to sell?', 'next' => 'shop'],
                                ['text' => 'Goodbye.', 'next' => 'end'],
                            ]
                        ],
                        'dangers' => [
                            'text'    => 'Creatures roam the forest to the west. Be careful.',
                            'choices' => [
                                ['text' => 'Thanks for the advice.', 'next' => 'start'],
                                ['text' => 'Goodbye.', 'next' => 'end'],
                            ]
                        ],
                        'shop' => [
                            'text'    => 'I have nothing to sell, but I can give you free advice.',
                            'choices' => [
                                ['text' => 'What advice?', 'next' => 'dangers'],
                                ['text' => 'No thanks.', 'next' => 'end'],
                            ]
                        ],
                        'end' => [
                            'text'    => 'Good luck, traveler.',
                            'choices' => []
                        ],
                    ]
                ],
            ],
        ];

        if (!isset($dialogues[$id])) {
            throw new \InvalidArgumentException("Dialogue inconnu : $id");
        }

        return $dialogues[$id][$lang] ?? $dialogues[$id]['fr'];
    }
}