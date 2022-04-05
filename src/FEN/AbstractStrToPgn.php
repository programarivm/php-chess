<?php

namespace Chess\FEN;

use Chess\Castle;
use Chess\FEN\BoardToStr;
use Chess\FEN\StrToBoard;
use Chess\PGN\Symbol;

abstract class AbstractStrToPgn
{
    protected $fromFen;

    protected $toFen;

    protected $board;

    public function __construct(string $fromFen, string $toFen)
    {
        $this->fromFen = $fromFen;
        $this->toFen = $toFen;
        $this->board = (new StrToBoard($fromFen))->create();
    }

    abstract protected function find(array $legal);

    public function create()
    {
        $legal = [];
        $color = $this->board->getTurn();
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            foreach ($piece->getSqs() as $sq) {
                $clone = unserialize(serialize($this->board));
                $id = $piece->getId();
                $position = $piece->getSquare();
                switch ($id) {
                    case Symbol::K:
                        $rule = Castle::color($color)[Symbol::K];
                        if ($sq === $rule[Symbol::O_O]['sq']['next'] &&
                            $this->board->getCastle()[$color][Symbol::O_O]
                        ) {
                            if ($clone->play($color, Symbol::K.$sq)) {
                                $legal[] = [
                                    Symbol::O_O => (new BoardToStr($clone))->create()
                                ];
                            }
                        } elseif ($sq === $rule[Symbol::O_O_O]['sq']['next'] &&
                            $this->board->getCastle()[$color][Symbol::O_O_O]
                        ) {
                            if ($clone->play($color, Symbol::K.$sq)) {
                                $legal[] = [
                                    Symbol::O_O_O => (new BoardToStr($clone))->create()
                                ];
                            }
                        } elseif ($clone->play($color, Symbol::K.$sq)) {
                            $legal[] = [
                                Symbol::K.$sq => (new BoardToStr($clone))->create()
                            ];
                        } elseif ($clone->play($color, Symbol::K.'x'.$sq)) {
                            $legal[] = [
                                Symbol::K.'x'.$sq => (new BoardToStr($clone))->create()
                            ];
                        }
                        break;
                    case Symbol::P:
                        try {
                            if ($clone->play($color, $sq)) {
                                $legal[] = [
                                    $sq => (new BoardToStr($clone))->create()
                                ];
                            }
                        } catch (\Exception $e) {}
                        if ($clone->play($color, $piece->getFile()."x$sq")) {
                            $legal[] = [
                                $piece->getFile()."x$sq" => (new BoardToStr($clone))->create()
                            ];
                        }
                        break;
                    default:
                        if (in_array($sq, $this->disambiguation($color, $id))) {
                            if ($clone->play($color, $id.$position.$sq)) {
                                $legal[] = [
                                    $id.$position.$sq => (new BoardToStr($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$id}{$position}x$sq")) {
                                $legal[] = [
                                    "{$id}{$position}x$sq" => (new BoardToStr($clone))->create()
                                ];
                            }
                        } else {
                            if ($clone->play($color, $id.$sq)) {
                                $legal[] = [
                                    $id.$sq => (new BoardToStr($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$id}x{$sq}")) {
                                $legal[] = [
                                    "{$id}x{$sq}" => (new BoardToStr($clone))->create()
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
            foreach ($piece->getSqs() as $sq) {
                switch ($piece->getId()) {
                    case Symbol::K:
                        break;
                    case Symbol::P:
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
