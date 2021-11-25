<?php

namespace Chess\FEN;

use Chess\Castling\Rule as CastlingRule;
use Chess\FEN\BoardToString;
use Chess\FEN\StringToBoard;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;

abstract class AbstractStringToPgn
{
    protected $fromFen;

    protected $toFen;

    protected $board;

    public function __construct(string $fromFen, string $toFen)
    {
        $this->fromFen = $fromFen;
        $this->toFen = $toFen;
        $this->board = (new StringToBoard($fromFen))->create();
    }

    abstract protected function find(array $legal);

    public function create()
    {
        $legal = [];
        $color = $this->board->getTurn();
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            foreach ($piece->getLegalMoves() as $square) {
                $clone = unserialize(serialize($this->board));
                $identity = $piece->getIdentity();
                $position = $piece->getPosition();
                switch ($identity) {
                    case Symbol::KING:
                        if ($square ===
                            CastlingRule::color($color)[Symbol::KING][Symbol::CASTLING_SHORT]['position']['next']
                        ) {
                            if ($clone->play(Convert::toStdObj($color, $square))) {
                                $legal[] = [
                                    Symbol::CASTLING_SHORT => (new BoardToString($clone))->create()
                                ];
                            }
                        } elseif ($square ===
                            CastlingRule::color($color)[Symbol::KING][Symbol::CASTLING_LONG]['position']['next']
                        ) {
                            if ($clone->play(Convert::toStdObj($color, $square))) {
                                $legal[] = [
                                    Symbol::CASTLING_LONG => (new BoardToString($clone))->create()
                                ];
                            }
                        } elseif ($clone->play(Convert::toStdObj($color, Symbol::KING.$square))) {
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
                        try {
                            if ($clone->play(Convert::toStdObj($color, $square))) {
                                $legal[] = [
                                    $square => (new BoardToString($clone))->create()
                                ];
                            }
                        } catch (\Exception $e) {}
                        if ($clone->play(Convert::toStdObj($color, $piece->getFile()."x$square"))) {
                            $legal[] = [
                                $piece->getFile()."x$square" => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                    default:
                        if (in_array($square, $this->disambiguation($color, $identity))) {
                            if ($clone->play(Convert::toStdObj($color, $identity.$position.$square))) {
                                $legal[] = [
                                    $identity.$position.$square => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play(Convert::toStdObj($color, "{$identity}{$position}x$square"))) {
                                $legal[] = [
                                    "{$identity}{$position}x$square" => (new BoardToString($clone))->create()
                                ];
                            }
                        } else {
                            if ($clone->play(Convert::toStdObj($color, $identity.$square))) {
                                $legal[] = [
                                    $identity.$square => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play(Convert::toStdObj($color, "{$identity}x{$square}"))) {
                                $legal[] = [
                                    "{$identity}x{$square}" => (new BoardToString($clone))->create()
                                ];
                            }
                        }
                        break;
                }
            }
        }

        return [
            $color => $this->find($legal),
        ];
    }

    protected function disambiguation(string $color, string $identity)
    {
        $identities = [];
        $clone = unserialize(serialize($this->board));
        foreach ($clone->getPiecesByColor($color) as $piece) {
            foreach ($piece->getLegalMoves() as $square) {
                switch ($piece->getIdentity()) {
                    case Symbol::KING:
                        break;
                    case Symbol::PAWN:
                        break;
                    default:
                        $identities[$piece->getIdentity()][$piece->getPosition()][] = $square;
                        break;
                }
            }
        }
        $vals = array_merge(...array_values($identities[$identity]));
        $duplicates = array_diff_assoc($vals, array_unique($vals));

        return $duplicates;
    }
}
