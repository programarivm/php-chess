<?php

namespace Chess;

use Chess\PGN\Symbol;

/**
 * Castling.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Castling
{
    const IS_CASTLED = 'isCastled';

    public static $initialState = [
        Symbol::WHITE => [
            self::IS_CASTLED => false,
            Symbol::CASTLING_SHORT => true,
            Symbol::CASTLING_LONG => true,
        ],
        Symbol::BLACK => [
            self::IS_CASTLED => false,
            Symbol::CASTLING_SHORT => true,
            Symbol::CASTLING_LONG => true,
        ],
    ];

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

    /*
     * Can castle short.
     *
     * @param string $color
     * @param array $castling
     * @param \stdClass $space
     * @return bool
     */
    public static function short(string $color, array $castling, \stdClass $space): bool
    {
        return $castling[$color][Symbol::CASTLING_SHORT] &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLING_SHORT]['sqs']['f'],
                $space->{Symbol::oppColor($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLING_SHORT]['sqs']['g'],
                $space->{Symbol::oppColor($color)})
             );
    }

    /*
     * Can castle long.
     *
     * @param string $color
     * @param array $castling
     * @param \stdClass $space
     * @return bool
     */
    public static function long(string $color, array $castling, \stdClass $space): bool
    {
        return $castling[$color][Symbol::CASTLING_LONG] &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLING_LONG]['sqs']['b'],
                $space->{Symbol::oppColor($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLING_LONG]['sqs']['c'],
                $space->{Symbol::oppColor($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLING_LONG]['sqs']['d'],
                $space->{Symbol::oppColor($color)})
             );
    }
}
