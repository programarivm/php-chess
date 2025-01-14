<?php

namespace Chess\Variant\Capablanca;

use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\CastlingRule as ClassicalCastlingRule;

class CastlingRule extends ClassicalCastlingRule
{
    public array $rule = [
        Color::W => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'g1', 'h1', 'i1' ],
                    'attack' => [ 'f1', 'g1', 'h1', 'i1' ],
                    'from' => 'f1',
                    'to' => 'i1',
                ],
                Castle::LONG => [
                    'free' => [ 'b1', 'c1', 'd1', 'e1' ],
                    'attack' => [ 'c1', 'd1', 'e1', 'f1' ],
                    'from' => 'f1',
                    'to' => 'c1',
                ],
            ],
            Piece::R => [
                Castle::SHORT => [
                    'from' => 'j1',
                    'to' => 'h1',
                ],
                Castle::LONG => [
                    'from' => 'a1',
                    'to' => 'd1',
                ],
            ],
        ],
        Color::B => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'g8', 'h8', 'i8' ],
                    'attack' => [ 'f8', 'g8', 'h8', 'i8' ],
                    'from' => 'f8',
                    'to' => 'i8',
                ],
                Castle::LONG => [
                    'free' => [ 'b8', 'c8', 'd8', 'e8' ],
                    'attack' => [ 'c8', 'd8', 'e8', 'f8' ],
                    'from' => 'f8',
                    'to' => 'c8',
                ],
            ],
            Piece::R => [
                Castle::SHORT => [
                    'from' => 'j8',
                    'to' => 'h8',
                ],
                Castle::LONG => [
                    'from' => 'a8',
                    'to' => 'd8',
                ],
            ],
        ],
    ];
}
