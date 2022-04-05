<?php

namespace Chess\PGN;

use Chess\Castle;
use Chess\Exception\UnknownNotationException;

/**
 * Convert class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Convert
{
    /**
     * PGN move to stdClass object.
     *
     * @param string $color
     * @param string $pgn
     * @return \stdClass
     * @throws \Chess\Exception\UnknownNotationException
     */
    public static function toStdClass(string $color, string $pgn): \stdClass
    {
        $isCheck = substr($pgn, -1) === '+' || substr($pgn, -1) === '#';
        if (preg_match('/^' . Move::KING . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::KING,
                'color' => Validate::color($color),
                'id' => Symbol::K,
                'sq' => (object) [
                    'current' => null,
                    'next' => mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::O_O . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::O_O,
                'color' => Validate::color($color),
                'id' => Symbol::K,
                'sq' => (object) Castle::color($color)[Symbol::K][Symbol::O_O]['sq'],
            ];
        } elseif (preg_match('/^' . Move::O_O_O . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::O_O_O,
                'color' => Validate::color($color),
                'id' => Symbol::K,
                'sq' => (object) Castle::color($color)[Symbol::K][Symbol::O_O_O]['sq'],
            ];
        } elseif (preg_match('/^' . Move::KING_CAPTURES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => Move::KING_CAPTURES,
                'color' => Validate::color($color),
                'id' => Symbol::K,
                'sq' => (object) [
                    'current' => null,
                    'next' => mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::PIECE . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::PIECE,
                'color' => Validate::color($color),
                'id' => mb_substr($pgn, 0, 1),
                'sq' => (object) [
                    'current' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -3), 1)
                        : mb_substr(mb_substr($pgn, 0, -2), 1),
                    'next' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -1), -2)
                        : mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::PIECE_CAPTURES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => Move::PIECE_CAPTURES,
                'color' => Validate::color($color),
                'id' => mb_substr($pgn, 0, 1),
                'sq' => (object) [
                    'current' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -4), 1)
                        : mb_substr(mb_substr($pgn, 0, -3), 1),
                    'next' => $isCheck
                        ? mb_substr($pgn, -3, -1)
                        : mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::KNIGHT . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::KNIGHT,
                'color' => Validate::color($color),
                'id' => Symbol::N,
                'sq' => (object) [
                    'current' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -3), 1)
                        : mb_substr(mb_substr($pgn, 0, -2), 1),
                    'next' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -1), -2)
                        : mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::KNIGHT_CAPTURES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => Move::KNIGHT_CAPTURES,
                'color' => Validate::color($color),
                'id' => Symbol::N,
                'sq' => (object) [
                    'current' => $isCheck
                        ? mb_substr(mb_substr($pgn, 0, -4), 1)
                        : mb_substr(mb_substr($pgn, 0, -3), 1),
                    'next' => $isCheck
                        ? mb_substr($pgn, -3, -1)
                        : mb_substr($pgn, -2)
                ],
            ];
        } elseif (preg_match('/^' . Move::PAWN_PROMOTES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::PAWN_PROMOTES,
                'color' => Validate::color($color),
                'id' => Symbol::P,
                'newId' => $isCheck ? mb_substr($pgn, -2, -1) : mb_substr($pgn, -1),
                'sq' => (object) [
                    'current' => null,
                    'next' => mb_substr($pgn, 0, 2)
                ],
            ];
        } elseif (preg_match('/^' . Move::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => Move::PAWN_CAPTURES_AND_PROMOTES,
                'color' => Validate::color($color),
                'id' => Symbol::P,
                'newId' => $isCheck
                    ? mb_substr($pgn, -2, -1)
                    : mb_substr($pgn, -1),
                'sq' => (object) [
                    'current' => null,
                    'next' => mb_substr($pgn, 2, 2)
                ],
            ];
        } elseif (preg_match('/^' . Move::PAWN . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => Move::PAWN,
                'color' => Validate::color($color),
                'id' => Symbol::P,
                'sq' => (object) [
                    'current' => mb_substr($pgn, 0, 1),
                    'next' => $isCheck ? mb_substr($pgn, 0, -1) : $pgn
                ],
            ];
        } elseif (preg_match('/^' . Move::PAWN_CAPTURES . '$/', $pgn)) {
            return (object) [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => Move::PAWN_CAPTURES,
                'color' => Validate::color($color),
                'id' => Symbol::P,
                'sq' => (object) [
                    'current' => mb_substr($pgn, 0, 1),
                    'next' => $isCheck
                        ? mb_substr($pgn, -3, -1)
                        : mb_substr($pgn, -2)
                ],
            ];
        }

        throw new UnknownNotationException;
    }

    /**
     * Piece identifier to class name.
     *
     * @param string $id
     * @return string
     * @throws \Chess\Exception\UnknownNotationException
     */
    public static function toClassName(string $id): string
    {
        if ($id === Symbol::B) {
            return (new \ReflectionClass('\Chess\Piece\Bishop'))->getName();
        } elseif ($id === Symbol::K) {
            return (new \ReflectionClass('\Chess\Piece\King'))->getName();
        } elseif ($id === Symbol::N) {
            return (new \ReflectionClass('\Chess\Piece\Knight'))->getName();
        } elseif ($id === Symbol::P) {
            return (new \ReflectionClass('\Chess\Piece\Pawn'))->getName();
        } elseif ($id === Symbol::Q) {
            return (new \ReflectionClass('\Chess\Piece\Queen'))->getName();
        } elseif ($id === Symbol::R) {
            return (new \ReflectionClass('\Chess\Piece\Rook'))->getName();
        }

        throw new UnknownNotationException;
    }

    /**
     * PGN color to its opposite color.
     *
     * @param string $color
     * @return string
     */
    public static function toOpposite(?string $color): string
    {
        if ($color == Symbol::WHITE) {
            return Symbol::BLACK;
        }

        return Symbol::WHITE;
    }
}
