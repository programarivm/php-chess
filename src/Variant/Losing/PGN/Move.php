<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Losing\PGN\AN\Piece;

class Move extends AbstractNotation
{
    const ELLIPSIS = '...';
    const KNIGHT = 'N[a-h]{0,1}[1-8]{0,1}' . Square::REGEX;
    const KNIGHT_CAPTURES = 'N[a-h]{0,1}[1-8]{0,1}x' . Square::REGEX;
    const PAWN = Square::REGEX;
    const PAWN_CAPTURES = '[a-h]{1}x' . Square::REGEX;
    const PAWN_PROMOTES = '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}';
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}';
    const PIECE = '[BRQM]{1}[a-h]{0,1}[1-8]{0,1}' . Square::REGEX;
    const PIECE_CAPTURES = '[BRQM]{1}[a-h]{0,1}[1-8]{0,1}x' . Square::REGEX;

    public function cases(): array
    {
        return (new \ReflectionClass(__CLASS__))->getConstants();
    }

    public function case(string $case): string
    {
        $key = array_search($case, $this->cases());

        return $this->cases()[$key];
    }

    public function extractSqs(string $string): string
    {
        return preg_replace(Square::EXTRACT, '', $string);
    }

    public function explodeSqs(string $string): array
    {
        preg_match_all('/'.Square::REGEX.'/', $string, $matches);

        return $matches[0];
    }

    public function validate(string $value): string
    {
        switch (true) {
            case preg_match('/^' . static::PIECE . '$/', $value):
                return $value;
            case preg_match('/^' . static::PIECE_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::KNIGHT . '$/', $value):
                return $value;
            case preg_match('/^' . static::KNIGHT_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_PROMOTES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $value):
                return $value;
        }

        throw new UnknownNotationException();
    }

    public function toArray(string $str, string $pgn, $castlingRule = null, Color $color): array
    {
        $isCheck = false;
        if (preg_match('/^' . static::PIECE . '$/', $pgn)) {
            $sqs = $this->extractSqs($pgn);
            $next = substr($sqs, -2);
            $current = str_replace($next, '', $sqs);
            return [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => static::PIECE,
                'color' => $color->validate($str),
                'id' => mb_substr($pgn, 0, 1),
                'sq' => [
                    'current' => $current,
                    'next' => $next,
                ],
            ];
        } elseif (preg_match('/^' . static::PIECE_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => static::PIECE_CAPTURES,
                'color' => $color->validate($str),
                'id' => mb_substr($pgn, 0, 1),
                'sq' => [
                    'current' => $this->extractSqs($arr[0]),
                    'next' => $this->extractSqs($arr[1]),
                ],
            ];
        } elseif (preg_match('/^' . static::KNIGHT . '$/', $pgn)) {
            $sqs = $this->extractSqs($pgn);
            $next = substr($sqs, -2);
            $current = str_replace($next, '', $sqs);
            return [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => static::KNIGHT,
                'color' => $color->validate($str),
                'id' => Piece::N,
                'sq' => [
                    'current' => $current,
                    'next' => $next,
                ],
            ];
        } elseif (preg_match('/^' . static::KNIGHT_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => static::KNIGHT_CAPTURES,
                'color' => $color->validate($str),
                'id' => Piece::N,
                'sq' => [
                    'current' => $this->extractSqs($arr[0]),
                    'next' => $this->extractSqs($arr[1]),
                ],
            ];
        } elseif (preg_match('/^' . static::PAWN_PROMOTES . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => static::PAWN_PROMOTES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'newId' => $isCheck
                    ? mb_substr($pgn, -2, -1)
                    : mb_substr($pgn, -1),
                'sq' => [
                    'current' => '',
                    'next' => $this->extractSqs($pgn),
                ],
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => static::PAWN_CAPTURES_AND_PROMOTES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'newId' => $isCheck
                    ? mb_substr($pgn, -2, -1)
                    : mb_substr($pgn, -1),
                'sq' => [
                    'current' => '',
                    'next' => $this->extractSqs($arr[1]),
                ],
            ];
        } elseif (preg_match('/^' . static::PAWN . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'isCapture' => false,
                'isCheck' => $isCheck,
                'type' => static::PAWN,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'sq' => [
                    'current' => mb_substr($pgn, 0, 1),
                    'next' => $this->extractSqs($pgn),
                ],
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'isCapture' => true,
                'isCheck' => $isCheck,
                'type' => static::PAWN_CAPTURES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'sq' => [
                    'current' => mb_substr($pgn, 0, 1),
                    'next' => $this->extractSqs($arr[1]),
                ],
            ];
        }

        throw new UnknownNotationException($pgn);
    }
}
