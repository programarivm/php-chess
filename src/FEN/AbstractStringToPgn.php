<?php

namespace Chess\FEN;

use Chess\Castling;
use Chess\FEN\BoardToString;
use Chess\FEN\StringToBoard;
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
            foreach ($piece->getSquares() as $sq) {
                $clone = unserialize(serialize($this->board));
                $id = $piece->getId();
                $position = $piece->getSquare();
                switch ($id) {
                    case Symbol::KING:
                        $rule = Castling::color($color)[Symbol::KING];
                        if ($sq === $rule[Symbol::CASTLING_SHORT]['sq']['next'] &&
                            $this->board->getCastling()[$color][Symbol::CASTLING_SHORT]
                        ) {
                            if ($clone->play($color, Symbol::KING.$sq)) {
                                $legal[] = [
                                    Symbol::CASTLING_SHORT => (new BoardToString($clone))->create()
                                ];
                            }
                        } elseif ($sq === $rule[Symbol::CASTLING_LONG]['sq']['next'] &&
                            $this->board->getCastling()[$color][Symbol::CASTLING_LONG]
                        ) {
                            if ($clone->play($color, Symbol::KING.$sq)) {
                                $legal[] = [
                                    Symbol::CASTLING_LONG => (new BoardToString($clone))->create()
                                ];
                            }
                        } elseif ($clone->play($color, Symbol::KING.$sq)) {
                            $legal[] = [
                                Symbol::KING.$sq => (new BoardToString($clone))->create()
                            ];
                        } elseif ($clone->play($color, Symbol::KING.'x'.$sq)) {
                            $legal[] = [
                                Symbol::KING.'x'.$sq => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                    case Symbol::PAWN:
                        try {
                            if ($clone->play($color, $sq)) {
                                $legal[] = [
                                    $sq => (new BoardToString($clone))->create()
                                ];
                            }
                        } catch (\Exception $e) {}
                        if ($clone->play($color, $piece->getFile()."x$sq")) {
                            $legal[] = [
                                $piece->getFile()."x$sq" => (new BoardToString($clone))->create()
                            ];
                        }
                        break;
                    default:
                        if (in_array($sq, $this->disambiguation($color, $id))) {
                            if ($clone->play($color, $id.$position.$sq)) {
                                $legal[] = [
                                    $id.$position.$sq => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$id}{$position}x$sq")) {
                                $legal[] = [
                                    "{$id}{$position}x$sq" => (new BoardToString($clone))->create()
                                ];
                            }
                        } else {
                            if ($clone->play($color, $id.$sq)) {
                                $legal[] = [
                                    $id.$sq => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$id}x{$sq}")) {
                                $legal[] = [
                                    "{$id}x{$sq}" => (new BoardToString($clone))->create()
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

    protected function disambiguation(string $color, string $id)
    {
        $identities = [];
        $clone = unserialize(serialize($this->board));
        foreach ($clone->getPiecesByColor($color) as $piece) {
            foreach ($piece->getSquares() as $sq) {
                switch ($piece->getId()) {
                    case Symbol::KING:
                        break;
                    case Symbol::PAWN:
                        break;
                    default:
                        $identities[$piece->getId()][$piece->getSquare()][] = $sq;
                        break;
                }
            }
        }
        $vals = array_merge(...array_values($identities[$id]));
        $duplicates = array_diff_assoc($vals, array_unique($vals));

        return $duplicates;
    }
}
