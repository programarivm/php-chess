<?php

namespace Chess;

use Chess\Variant\AbstractBoard;;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\FenToBoardFactory as CapablancaFenToBoardFactory;
use Chess\Variant\CapablancaFischer\Board as CapablancaFischerBoard;
use Chess\Variant\CapablancaFischer\FenToBoardFactory as CapablancaFischerFenToBoardFactory;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Chess960\FenToBoardFactory as Chess960FenToBoardFactory;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FenToBoardFactory as ClassicalFenToBoardFactory;
use Chess\Variant\Dunsany\Board as DunsanyBoard;
use Chess\Variant\Dunsany\FenToBoardFactory as DunsanyFenToBoardFactory;
use Chess\Variant\Losing\Board as LosingBoard;
use Chess\Variant\Losing\FenToBoardFactory as LosingFenToBoardFactory;
use Chess\Variant\RacingKings\Board as RacingKingsBoard;
use Chess\Variant\RacingKings\FenToBoardFactory as RackingKingsFenToBoardFactory;

class FenToBoardFactory
{
    public static function create(string $fen, AbstractBoard $board = null)
    {
        $board ??= new ClassicalBoard();

        if (is_a($board, CapablancaBoard::class)) {
            return CapablancaFenToBoardFactory::create($fen);
        } elseif (is_a($board, CapablancaFischerBoard::class)) {
            $startPos = $board->getStartPos();
            return CapablancaFischerFenToBoardFactory::create($fen, $board->getStartPos());
        } elseif (is_a($board, Chess960Board::class)) {
            return Chess960FenToBoardFactory::create($fen, $board->getStartPos());
        } elseif (is_a($board, DunsanyBoard::class)) {
            return DunsanyFenToBoardFactory::create($fen);
        } elseif (is_a($board, LosingBoard::class)) {
            return LosingFenToBoardFactory::create($fen);
        } elseif (is_a($board, RacingKingsBoard::class)) {
            return RackingKingsFenToBoardFactory::create($fen);
        }

        return ClassicalFenToBoardFactory::create($fen);
    }
}
