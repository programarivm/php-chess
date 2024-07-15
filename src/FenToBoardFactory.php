<?php

namespace Chess;

use Chess\Variant\AbstractBoard;;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\FEN\StrToBoard as CapablancaFenStrToBoard;
use Chess\Variant\CapablancaFischer\Board as CapablancaFischerBoard;
use Chess\Variant\CapablancaFischer\FEN\StrToBoard as CapablancaFischerFenStrToBoard;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Chess960\FEN\StrToBoard as Chess960FenStrToBoard;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Dunsany\Board as DunsanyBoard;
use Chess\Variant\Dunsany\FEN\StrToBoard as DunsanyFenStrToBoard;
use Chess\Variant\Losing\Board as LosingBoard;
use Chess\Variant\Losing\FEN\StrToBoard as LosingFenStrToBoard;
use Chess\Variant\RacingKings\Board as RacingKingsBoard;
use Chess\Variant\RacingKings\FEN\StrToBoard as RacingKingsStrToBoard;

class FenToBoardFactory
{
    public static function create(string $fen, AbstractBoard $board = null)
    {
        $board ??= new ClassicalBoard();

        if (is_a($board, CapablancaBoard::class)) {
            return (new CapablancaFenStrToBoard($fen))->create();
        } elseif (is_a($board, CapablancaFischerBoard::class)) {
            $startPos = $board->getStartPos();
            return (new CapablancaFischerFenStrToBoard($fen, $startPos))->create();
        } elseif (is_a($board, Chess960Board::class)) {
            $startPos = $board->getStartPos();
            return (new Chess960FenStrToBoard($fen, $startPos))->create();
        } elseif (is_a($board, DunsanyBoard::class)) {
            return (new DunsanyFenStrToBoard($fen))->create();
        } elseif (is_a($board, LosingBoard::class)) {
            return (new LosingFenStrToBoard($fen))->create();
        } elseif (is_a($board, RacingKingsBoard::class)) {
            return (new RacingKingsStrToBoard($fen))->create();
        }

        return (new ClassicalFenStrToBoard($fen))->create();
    }
}
