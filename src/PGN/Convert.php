<?php

namespace PGNChess\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\Square\Castling;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;

/**
 * Convert class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Convert
{
    /**
     * Converts a PGN move into a stdClass object.
     *
     * @param string $color
     * @param string $pgn
     * @return \stdClass
     * @throws \PGNChess\Exception\UnknownNotationException
     */
    public static function toObject(string $color, string $pgn): \stdClass
    {
        $isCheck = substr($pgn, -1) === '+' || substr($pgn, -1) === '#';

        switch(true) {
            case preg_match('/^' . Move::KING . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Move::KING_CASTLING_SHORT . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CASTLING_SHORT,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KING,
                    'position' => Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position
                ];
                break;

            case preg_match('/^' . Move::KING_CASTLING_LONG . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CASTLING_LONG,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KING,
                    'position' => Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->position
                ];
                break;

            case preg_match('/^' . Move::KING_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::KING_CAPTURES,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KING,
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, -2)
                ]];
                break;

            case preg_match('/^' . Move::PIECE . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PIECE,
                    'color' => PgnValidate::color($color),
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Move::PIECE_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PIECE_CAPTURES,
                    'color' => PgnValidate::color($color),
                    'identity' => mb_substr($pgn, 0, 1),
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::KNIGHT . '$/', $pgn):
                if (!$isCheck) {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -2), 1);
                    $nextPosition = mb_substr($pgn, -2);
                } else {
                    $currentPosition = mb_substr(mb_substr($pgn, 0, -3), 1);
                    $nextPosition = mb_substr(mb_substr($pgn, 0, -1), -2);
                }
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::KNIGHT,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => $currentPosition,
                        'next' => $nextPosition
                ]];
                break;

            case preg_match('/^' . Move::KNIGHT_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::KNIGHT_CAPTURES,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::KNIGHT,
                    'position' => (object) [
                        'current' => !$isCheck ? mb_substr(mb_substr($pgn, 0, -3), 1) : mb_substr(mb_substr($pgn, 0, -4), 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? $pgn : mb_substr($pgn, 0, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_CAPTURES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_CAPTURES,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::PAWN,
                    'position' => (object) [
                        'current' => mb_substr($pgn, 0, 1),
                        'next' => !$isCheck ? mb_substr($pgn, -2) : mb_substr($pgn, -3, -1)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => false,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_PROMOTES,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 0, 2)
                ]];
                break;

            case preg_match('/^' . Move::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn):
                return (object) [
                    'pgn' => $pgn,
                    'isCapture' => true,
                    'isCheck' => $isCheck,
                    'type' => Move::PAWN_CAPTURES_AND_PROMOTES,
                    'color' => PgnValidate::color($color),
                    'identity' => Symbol::PAWN,
                    'newIdentity' => !$isCheck ? mb_substr($pgn, -1) : mb_substr($pgn, -2, -1),
                    'position' => (object) [
                        'current' => null,
                        'next' => mb_substr($pgn, 2, 2)
                ]];
                break;

            default:
                throw new UnknownNotationException("Unknown PGN notation.");
                break;
        }
    }

    /**
     * Gets the piece's PGN Chess class name according to its PGN identity.
     *
     * @param string $identity
     * @return string
     */
    public static function toClassName(string $identity): string
    {
        switch($identity) {
            case Symbol::BISHOP:
                return (new \ReflectionClass('\PGNChess\Piece\Bishop'))->getName();
                break;
            case Symbol::KING:
                return (new \ReflectionClass('\PGNChess\Piece\King'))->getName();
                break;
            case Symbol::KNIGHT:
                return (new \ReflectionClass('\PGNChess\Piece\Knight'))->getName();
                break;
            case Symbol::PAWN:
                return (new \ReflectionClass('\PGNChess\Piece\Pawn'))->getName();
                break;
            case Symbol::QUEEN:
                return (new \ReflectionClass('\PGNChess\Piece\Queen'))->getName();
                break;
            case Symbol::ROOK:
                return (new \ReflectionClass('\PGNChess\Piece\Rook'))->getName();
                break;
        }
    }
}
