<?php

namespace Chess\Castling;

use Chess\PGN\Symbol;

/**
 * Castling rule.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Rule
{
    const IS_CASTLED = 'isCastled';

    /**
     * Castling rule by color.
     *
     * @param string $color
     * @return array
     */
    public static function color(string $color): array
    {
        switch ($color) {
            case Symbol::WHITE:
                return [
                    Symbol::KING => [
                        Symbol::CASTLING_SHORT => [
                            'sqs' => [
                                'f' => 'f1',
                                'g' => 'g1',
                            ],
                            'sq' => [
                                'current' => 'e1',
                                'next' => 'g1',
                            ],
                        ],
                        Symbol::CASTLING_LONG => [
                            'sqs' => [
                                'b' => 'b1',
                                'c' => 'c1',
                                'd' => 'd1',
                            ],
                            'sq' => [
                                'current' => 'e1',
                                'next' => 'c1',
                            ],
                        ],
                    ],
                    Symbol::ROOK => [
                        Symbol::CASTLING_SHORT => [
                            'sq' => [
                                'current' => 'h1',
                                'next' => 'f1',
                            ],
                        ],
                        Symbol::CASTLING_LONG => [
                            'sq' => [
                                'current' => 'a1',
                                'next' => 'd1',
                            ],
                        ],
                    ],
                ];

            case Symbol::BLACK:
                return [
                    Symbol::KING => [
                        Symbol::CASTLING_SHORT => [
                            'sqs' => [
                                'f' => 'f8',
                                'g' => 'g8',
                            ],
                            'sq' => [
                                'current' => 'e8',
                                'next' => 'g8',
                            ],
                        ],
                        Symbol::CASTLING_LONG => [
                            'sqs' => [
                                'b' => 'b8',
                                'c' => 'c8',
                                'd' => 'd8',
                            ],
                            'sq' => [
                                'current' => 'e8',
                                'next' => 'c8',
                            ],
                        ],
                    ],
                    Symbol::ROOK => [
                        Symbol::CASTLING_SHORT => [
                            'sq' => [
                                'current' => 'h8',
                                'next' => 'f8',
                            ],
                        ],
                        Symbol::CASTLING_LONG => [
                            'sq' => [
                                'current' => 'a8',
                                'next' => 'd8',
                            ],
                        ],
                    ],
                ];
        }
    }
}
