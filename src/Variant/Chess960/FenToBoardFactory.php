<?php

namespace Chess\Variant\Chess960;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractBoard;
use Chess\Variant\PieceArrayFactory;
use Chess\Variant\Chess960\FEN\Str;
use Chess\Variant\Classical\FenToBoardFactory as ClassicalFenToBoardFactory;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

/**
 * FEN to Board Factory
 *
 * A factory of Chess960 boards.
 */
class FenToBoardFactory
{
    /**
     * @param string|null $string
     * @return \Chess\Variant\AbstractBoard
     */
    public static function create(string $string = null): AbstractBoard
    {
        if (!$string) {
            return new Board((new Shuffle())->shuffle());
        }

        $fenStr = new Str();
        $string = $fenStr->validate($string);
        $fields = array_filter(explode(' ', $string));
        $namespace = 'Classical';
        $shuffle = (new Shuffle())->extract($string);
        $castlingRule = new CastlingRule($shuffle);

        try {
            $pieces = PieceArrayFactory::create(
                $fenStr->toArray($fields[0]),
                new Square(),
                $castlingRule,
                $namespace
            );
            $castlingAbility = self::replaceChars($fields[2], $castlingRule);
            $board = new Board($shuffle, $pieces, $castlingAbility);
            $board->turn = $fields[1];
            $board->startFen = "{$fields[0]} {$fields[1]} {$castlingAbility} {$fields[3]}";
            ClassicalFenToBoardFactory::enPassant($fields, $board);
        } catch (\Throwable $e) {
            throw new UnknownNotationException();
        }

        return $board;
    }

    public static function replaceChars(string $castlingAbility, $castlingRule) 
    {
        $replaced = '';
        foreach (str_split($castlingAbility) as $val) {
            if (ctype_upper($val)) {
                if ($val === 'Q') {
                    $replaced .= 'Q';
                } elseif ($val === 'K') {
                    $replaced .= 'K';
                } elseif (self::isLikeQ($val, $castlingRule)) {
                    $replaced .= 'Q';
                } elseif (self::isLikeK($val, $castlingRule)) {
                    $replaced .= 'K';
                }
            } else {
                if ($val === 'q') {
                    $replaced .= 'q';
                } elseif ($val === 'k') {
                    $replaced .= 'k';
                } elseif (self::isLikeQ($val, $castlingRule)) {
                    $replaced .= 'q';
                } elseif (self::isLikeK($val, $castlingRule)) {
                    $replaced .= 'k';
                }
            }
        }
        $replaced = str_replace('QK', 'KQ', $replaced);
        $replaced = str_replace('qk', 'kq', $replaced);
  
        return $replaced;
    }

    public static function isLikeQ(string $char, $castlingRule): bool
    {
        foreach ($castlingRule->startFiles as $key => $val) {
            if ($val === Piece::K) {
                return mb_strtolower($char) > $key;
            }
        }

        return false;
    }

    public static function isLikeK(string $char, $castlingRule): bool
    {
        foreach ($castlingRule->startFiles as $key => $val) {
            if ($val === Piece::K) {
                return mb_strtolower($char) < $key;
            }
        }

        return false;
    }
}
