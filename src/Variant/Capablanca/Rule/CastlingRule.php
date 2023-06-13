<?php

namespace Chess\Variant\Capablanca\Rule;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Rule\CastlingRule as ClassicalCastlingRule;

class CastlingRule extends ClassicalCastlingRule
{
    protected array $rule = [
        Color::W => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'g1', 'h1', 'i1' ],
                    'attack' => [ 'f1', 'g1', 'h1', 'i1' ],
                    'sq' => [
                        'current' => 'f1',
                        'next' => 'i1',
                    ],
                ],
                Castle::LONG => [
                    'free' => [ 'b1', 'c1', 'd1', 'e1' ],
                    'attack' => [ 'c1', 'd1', 'e1', 'f1' ],
                    'sq' => [
                        'current' => 'f1',
                        'next' => 'c1',
                    ],
                ],
            ],
            Piece::R => [
                Castle::SHORT => [
                    'sq' => [
                        'current' => 'j1',
                        'next' => 'h1',
                    ],
                ],
                Castle::LONG => [
                    'sq' => [
                        'current' => 'a1',
                        'next' => 'd1',
                    ],
                ],
            ],
        ],
        Color::B => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'g8', 'h8', 'i8' ],
                    'attack' => [ 'f8', 'g8', 'h8', 'i8' ],
                    'sq' => [
                        'current' => 'f8',
                        'next' => 'i8',
                    ],
                ],
                Castle::LONG => [
                    'free' => [ 'b8', 'c8', 'd8', 'e8' ],
                    'attack' => [ 'c8', 'd8', 'e8', 'f8' ],
                    'sq' => [
                        'current' => 'f8',
                        'next' => 'c8',
                    ],
                ],
            ],
            Piece::R => [
                Castle::SHORT => [
                    'sq' => [
                        'current' => 'j8',
                        'next' => 'h8',
                    ],
                ],
                Castle::LONG => [
                    'sq' => [
                        'current' => 'a8',
                        'next' => 'd8',
                    ],
                ],
            ],
        ],
    ];
}
