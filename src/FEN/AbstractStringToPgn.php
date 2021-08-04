<?php

namespace Chess\FEN;

use Chess\FEN\BoardToString;
use Chess\FEN\StringToBoard;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;

abstract class AbstractStringToPgn
{
    protected $fromFen;

    protected $toFen;

    public function __construct(string $fromFen, string $toFen)
    {
        $this->fromFen = $fromFen;

        $this->toFen = $toFen;
    }

    abstract protected function find(array $legal);

    public function create()
    {
        $legal = [];
        $board = (new StringToBoard($this->fromFen))->create();
        $color = $board->getTurn();
        foreach ($board->getPiecesByColor($color) as $piece) {
            foreach ($piece->getLegalMoves() as $square) {
                $clone = unserialize(serialize($board));
                switch ($piece->getIdentity()) {
                    case Symbol::KING:
                        if ($clone->play(Convert::toStdObj($color, Symbol::KING.$square))) {
                            $legal[] = [
                                Symbol::KING.$square => (new BoardToString($clone))->create()
                            ];
                        } elseif ($clone->play(Convert::toStdObj($color, Symbol::KING.'x'.$square))) {
                            $legal[] = [
                                Symbol::KING.'x'.$square => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                    case Symbol::PAWN:
                        if ($clone->play(Convert::toStdObj($color, $square))) {
                            $legal[] = [
                                $square => (new BoardToString($clone))->create()
                            ];
                        } elseif ($clone->play(Convert::toStdObj($color, $piece->getFile()."x$square"))) {
                            $legal[] = [
                                $piece->getFile()."x$square" => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                    default:
                        if ($clone->play(Convert::toStdObj($color, $piece->getIdentity().$square))) {
                            $legal[] = [
                                $piece->getIdentity().$piece->getPosition().$square => (new BoardToString($clone))->create()
                            ];
                        } elseif ($clone->play(Convert::toStdObj($color, "{$piece->getIdentity()}x$square"))) {
                            $legal[] = [
                                "{$piece->getIdentity()}{$piece->getPosition()}x$square" => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                }
            }
        }

        $clone = unserialize(serialize($board));

        if ($clone->play(Convert::toStdObj($color, Symbol::CASTLING_SHORT))) {
            $legal[] = [
                Symbol::CASTLING_SHORT => (new BoardToString($clone))->create()
            ];
        } elseif ($clone->play(Convert::toStdObj($color, Symbol::CASTLING_LONG))) {
            $legal[] = [
                Symbol::CASTLING_LONG => (new BoardToString($clone))->create()
            ];
        }

        return [
            $color => $this->find($legal),
        ];
    }
}
