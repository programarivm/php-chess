<?php

namespace Chess;

use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;

/**
 * Castle rule.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class CastlingRule
{
    /**
     * Castle rule by color.
     *
     * @param string $color
     * @return array
     */
    public static function color(string $color): array
    {
        switch ($color) {
            case Color::W:
                return [
                    Piece::K => [
                        Castle::SHORT => [
                            'sqs' => [
                                'f' => 'f1',
                                'g' => 'g1',
                            ],
                            'sq' => [
                                'current' => 'e1',
                                'next' => 'g1',
                            ],
                        ],
                        Castle::LONG => [
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
                    Piece::R => [
                        Castle::SHORT => [
                            'sq' => [
                                'current' => 'h1',
                                'next' => 'f1',
                            ],
                        ],
                        Castle::LONG => [
                            'sq' => [
                                'current' => 'a1',
                                'next' => 'd1',
                            ],
                        ],
                    ],
                ];

            case Color::B:
                return [
                    Piece::K => [
                        Castle::SHORT => [
                            'sqs' => [
                                'f' => 'f8',
                                'g' => 'g8',
                            ],
                            'sq' => [
                                'current' => 'e8',
                                'next' => 'g8',
                            ],
                        ],
                        Castle::LONG => [
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
                    Piece::R => [
                        Castle::SHORT => [
                            'sq' => [
                                'current' => 'h8',
                                'next' => 'f8',
                            ],
                        ],
                        Castle::LONG => [
                            'sq' => [
                                'current' => 'a8',
                                'next' => 'd8',
                            ],
                        ],
                    ],
                ];
        }
    }

    /**
     * Can castle short.
     *
     * @param string $color
     * @param string $castlingAbility
     * @param object $space
     * @return bool
     */
    public static function short(string $castlingAbility, string $color, object $space): bool
    {
        return CastlingAbility::short($castlingAbility, $color) &&
            !(in_array(
                self::color($color)[Piece::K][Castle::SHORT]['sqs']['f'],
                $space->{Color::opp($color)})
             ) &&
            !(in_array(
                self::color($color)[Piece::K][Castle::SHORT]['sqs']['g'],
                $space->{Color::opp($color)})
             );
    }

    /**
     * Can castle long.
     *
     * @param string $color
     * @param string $castlingAbility
     * @param object $space
     * @return bool
     */
    public static function long(string $castlingAbility, string $color, object $space): bool
    {
        return CastlingAbility::long($castlingAbility, $color) &&
            !(in_array(
                self::color($color)[Piece::K][Castle::LONG]['sqs']['b'],
                $space->{Color::opp($color)})
             ) &&
            !(in_array(
                self::color($color)[Piece::K][Castle::LONG]['sqs']['c'],
                $space->{Color::opp($color)})
             ) &&
            !(in_array(
                self::color($color)[Piece::K][Castle::LONG]['sqs']['d'],
                $space->{Color::opp($color)})
             );
    }
}
