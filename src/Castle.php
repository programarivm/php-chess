<?php

namespace Chess;

use Chess\PGN\Convert;
use Chess\PGN\Symbol;

/**
 * Castle.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Castle
{
    const IS_CASTLED = 'isCastled';

    public static $initialState = [
        Symbol::WHITE => [
            self::IS_CASTLED => false,
            Symbol::O_O => true,
            Symbol::O_O_O => true,
        ],
        Symbol::BLACK => [
            self::IS_CASTLED => false,
            Symbol::O_O => true,
            Symbol::O_O_O => true,
        ],
    ];

    /**
     * Castle rule by color.
     *
     * @param string $color
     * @return array
     */
    public static function color(string $color): array
    {
        switch ($color) {
            case Symbol::WHITE:
                return [
                    Symbol::K => [
                        Symbol::O_O => [
                            'sqs' => [
                                'f' => 'f1',
                                'g' => 'g1',
                            ],
                            'sq' => [
                                'current' => 'e1',
                                'next' => 'g1',
                            ],
                        ],
                        Symbol::O_O_O => [
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
                    Symbol::R => [
                        Symbol::O_O => [
                            'sq' => [
                                'current' => 'h1',
                                'next' => 'f1',
                            ],
                        ],
                        Symbol::O_O_O => [
                            'sq' => [
                                'current' => 'a1',
                                'next' => 'd1',
                            ],
                        ],
                    ],
                ];

            case Symbol::BLACK:
                return [
                    Symbol::K => [
                        Symbol::O_O => [
                            'sqs' => [
                                'f' => 'f8',
                                'g' => 'g8',
                            ],
                            'sq' => [
                                'current' => 'e8',
                                'next' => 'g8',
                            ],
                        ],
                        Symbol::O_O_O => [
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
                    Symbol::R => [
                        Symbol::O_O => [
                            'sq' => [
                                'current' => 'h8',
                                'next' => 'f8',
                            ],
                        ],
                        Symbol::O_O_O => [
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
     * @param array $castle
     * @param \stdClass $space
     * @return bool
     */
    public static function short(string $color, array $castle, \stdClass $space): bool
    {
        return $castle[$color][Symbol::O_O] &&
            !(in_array(
                self::color($color)[Symbol::K][Symbol::O_O]['sqs']['f'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::K][Symbol::O_O]['sqs']['g'],
                $space->{Convert::toOpposite($color)})
             );
    }

    /*
     * Can castle long.
     *
     * @param string $color
     * @param array $castle
     * @param \stdClass $space
     * @return bool
     */
    public static function long(string $color, array $castle, \stdClass $space): bool
    {
        return $castle[$color][Symbol::O_O_O] &&
            !(in_array(
                self::color($color)[Symbol::K][Symbol::O_O_O]['sqs']['b'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::K][Symbol::O_O_O]['sqs']['c'],
                $space->{Convert::toOpposite($color)})
             ) &&
            !(in_array(
                self::color($color)[Symbol::K][Symbol::O_O_O]['sqs']['d'],
                $space->{Convert::toOpposite($color)})
             );
    }
}
