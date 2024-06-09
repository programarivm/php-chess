<?php

namespace Chess\Variant\Classical\Rule;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class CastlingRule
{
    const START = 'KQkq';

    const NEITHER = '-';

    protected array $rule = [
        Color::W => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'f1', 'g1' ],
                    'attack' => [ 'e1', 'f1', 'g1' ],
                    'sq' => [
                        'current' => 'e1',
                        'next' => 'g1',
                    ],
                ],
                Castle::LONG => [
                    'free' => [ 'b1', 'c1', 'd1' ],
                    'attack' => [ 'c1', 'd1', 'e1' ],
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
        ],
        Color::B => [
            Piece::K => [
                Castle::SHORT => [
                    'free' => [ 'f8', 'g8' ],
                    'attack' => [ 'e8', 'f8', 'g8' ],
                    'sq' => [
                        'current' => 'e8',
                        'next' => 'g8',
                    ],
                ],
                Castle::LONG => [
                    'free' => [ 'b8', 'c8', 'd8' ],
                    'attack' => [ 'c8', 'd8', 'e8' ],
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
        ],
    ];

    public function getRule(): array
    {
        return $this->rule;
    }

    /**
     * String validation.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public function validate(string $value): string
    {
        if ($value === self::NEITHER) {
            return $value;
        } elseif ($value && preg_match('/^K?Q?k?q?$/', $value)) {
            return $value;
        }

        throw new UnknownNotationException();
    }

    /**
     * Removes the given castling ability.
     *
     * @param string $castlingAbility
     * @param string $color
     * @param array $ids
     * @return string
     */
    public function remove(string $castlingAbility, string $color, array $ids): string
    {
        if ($color === Color::B) {
            $ids = array_map('mb_strtolower', $ids);
        }
        $castlingAbility = str_replace($ids, '', $castlingAbility);
        if (empty($castlingAbility)) {
            $castlingAbility = self::NEITHER;
        }

        return $castlingAbility;
    }

    /**
     * Castles the king.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public function castle(string $castlingAbility, string $color): string
    {
        $castlingAbility = self::remove(
            $castlingAbility,
            $color,
            [ Piece::K, Piece::Q ],
        );

        return $castlingAbility;
    }

    /**
     * Finds out if a long castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public function long(string $castlingAbility, string $color): string
    {
        $id = Piece::Q;
        if ($color === Color::B) {
            $id = mb_strtolower($id);
        }

        return strpbrk($castlingAbility, $id);
    }

    /**
     * Finds out if a short castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public function short(string $castlingAbility, string $color)
    {
        $id = Piece::K;
        if ($color === Color::B) {
            $id = mb_strtolower($id);
        }

        return strpbrk($castlingAbility, $id);
    }

    /**
     * Finds out if a castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return bool
     */
    public function can(string $castlingAbility, string $color)
    {
        return self::long($castlingAbility, $color) ||
            self::short($castlingAbility, $color);
    }
}
