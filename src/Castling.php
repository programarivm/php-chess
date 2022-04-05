<?php

namespace Chess;

use Chess\PGN\Convert;
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
            Symbol::CASTLE_SHORT => true,
            Symbol::CASTLE_LONG => true,
        ],
        Symbol::BLACK => [
            self::IS_CASTLED => false,
            Symbol::CASTLE_SHORT => true,
            Symbol::CASTLE_LONG => true,
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
                        Symbol::CASTLE_SHORT => [
                            'sqs' => [
                                'f' => 'f1',
                                'g' => 'g1',
                            ],
                            'sq' => [
                                'current' => 'e1',
                                'next' => 'g1',
                            ],
                        ],
                        Symbol::CASTLE_LONG => [
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
                        Symbol::CASTLE_SHORT => [
                            'sq' => [
                                'current' => 'h1',
                                'next' => 'f1',
                            ],
                        ],
                        Symbol::CASTLE_LONG => [
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
                        Symbol::CASTLE_SHORT => [
                            'sqs' => [
                                'f' => 'f8',
                                'g' => 'g8',
                            ],
                            'sq' => [
                                'current' => 'e8',
                                'next' => 'g8',
                            ],
                        ],
                        Symbol::CASTLE_LONG => [
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
                        Symbol::CASTLE_SHORT => [
                            'sq' => [
                                'current' => 'h8',
                                'next' => 'f8',
                            ],
                        ],
                        Symbol::CASTLE_LONG => [
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
        return $castling[$color][Symbol::CASTLE_SHORT] &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLE_SHORT]['sqs']['f'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLE_SHORT]['sqs']['g'],
                $space->{Convert::toOpposite($color)})
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
        return $castling[$color][Symbol::CASTLE_LONG] &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLE_LONG]['sqs']['b'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLE_LONG]['sqs']['c'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::KING][Symbol::CASTLE_LONG]['sqs']['d'],
                $space->{Convert::toOpposite($color)})
             );
    }
}
