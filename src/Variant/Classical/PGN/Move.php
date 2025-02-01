<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractNotation;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class Move extends AbstractNotation
{
    const CHECK = '[\+\#]{0,1}';
    const CASTLE_SHORT = Castle::SHORT . self::CHECK;
    const CASTLE_LONG = Castle::LONG . self::CHECK;
    const ELLIPSIS = '...';
    const PAWN = Square::AN . self::CHECK;
    const PAWN_CAPTURES = '[a-h]{1}x' . Square::AN . self::CHECK;
    const PAWN_PROMOTES = Square::AN . '[=]{0,1}[NBRQ]{0,1}' . self::CHECK;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}' . self::CHECK;
    const PIECE = '[BKNQR]{1}[a-h]{0,1}[1-8]{0,1}' . Square::AN . self::CHECK;
    const PIECE_CAPTURES = '[BKNQR]{1}[a-h]{0,1}[1-8]{0,1}x' . Square::AN . self::CHECK;

    public function cases(): array
    {
        return (new \ReflectionClass(__CLASS__))->getConstants();
    }

    public function case(string $case): string
    {
        $key = array_search($case, $this->cases());

        return $this->cases()[$key];
    }

    public function validate(string $value): string
    {
        switch (true) {
            case preg_match('/^' . static::PIECE . '$/', $value):
                return $value;
            case preg_match('/^' . static::PIECE_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::CASTLE_SHORT . '$/', $value):
                return $value;
            case preg_match('/^' . static::CASTLE_LONG . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_PROMOTES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $value):
                return $value;
        }

        throw new UnknownNotationException();
    }

    public function toArray(string $color, string $pgn, Square $square, CastlingRule $castlingRule = null): array
    {
        if (preg_match('/^' . static::PIECE . '$/', $pgn)) {
            $sqs = $square->substr($pgn);
            $to = substr($sqs, -2);
            $from = str_replace($to, '', $sqs);
            return [
                'pgn' => $pgn,
                'case' => static::PIECE,
                'color' => $color,
                'id' => mb_substr($pgn, 0, 1),
                'from' => $from,
                'to' => $to,
            ];
        } elseif (preg_match('/^' . static::PIECE_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PIECE_CAPTURES,
                'color' => $color,
                'id' => mb_substr($pgn, 0, 1),
                'from' => $square->substr($arr[0]),
                'to' => $square->substr($arr[1]),
            ];
        } elseif (preg_match('/^' . static::PAWN . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::PAWN,
                'color' => $color,
                'id' => Piece::P,
                'from' => mb_substr($pgn, 0, 1),
                'to' => $square->substr($pgn),
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_CAPTURES,
                'color' => $color,
                'id' => Piece::P,
                'from' => mb_substr($pgn, 0, 1),
                'to' => $square->substr($arr[1]),
            ];
        } elseif (preg_match('/^' . static::CASTLE_SHORT . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::CASTLE_SHORT,
                'color' => $color,
                'id' => Piece::K,
                'from' => $castlingRule?->rule[$color][Piece::K][Castle::SHORT]['from'],
                'to' => $castlingRule?->rule[$color][Piece::K][Castle::SHORT]['to'],
            ];
        } elseif (preg_match('/^' . static::CASTLE_LONG . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::CASTLE_LONG,
                'color' => $color,
                'id' => Piece::K,
                'from' => $castlingRule?->rule[$color][Piece::K][Castle::LONG]['from'],
                'to' => $castlingRule?->rule[$color][Piece::K][Castle::LONG]['to'],
            ];
        } elseif (preg_match('/^' . static::PAWN_PROMOTES . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_PROMOTES,
                'color' => $color,
                'id' => Piece::P,
                'newId' => substr(explode('=', $pgn)[1], 0, 1),
                'from' => '',
                'to' => $square->substr($pgn),
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_CAPTURES_AND_PROMOTES,
                'color' => $color,
                'id' => Piece::P,
                'newId' => substr(explode('=', $pgn)[1], 0, 1),
                'from' => '',
                'to' => $square->substr($arr[1]),
            ];
        }

        throw new UnknownNotationException($pgn);
    }
}
