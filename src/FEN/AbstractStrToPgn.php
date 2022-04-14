<?php

namespace Chess\FEN;

use Chess\Board;
use Chess\CastlingRule;
use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Piece;

abstract class AbstractStrToPgn
{
    protected string $fromFen;

    protected string $toFen;

    protected Board $board;

    public function __construct(string $fromFen, string $toFen)
    {
        $this->fromFen = $fromFen;
        $this->toFen = $toFen;
        $this->board = (new StrToBoard($fromFen))->create();
    }

    abstract protected function find(array $legal): ?string;

    public function create(): array
    {
        $legal = [];
        $color = $this->board->getTurn();
        foreach ($this->board->getPiecesByColor($color) as $piece) {
            foreach ($piece->getSqs() as $sq) {
                $clone = unserialize(serialize($this->board));
                $id = $piece->getId();
                $position = $piece->getSquare();
                switch ($id) {
                    case Piece::K:
                        $rule = CastlingRule::color($color)[Piece::K];
                        if ($sq === $rule[Castle::SHORT]['sq']['next'] &&
                            CastlingAbility::short($this->board->getCastlingAbility(), $color)
                        ) {
                            if ($clone->play($color, Piece::K.$sq)) {
                                $legal[] = [
                                    Castle::SHORT => (new BoardToStr($clone))->create()
                                ];
                            }
                        } elseif ($sq === $rule[Castle::LONG]['sq']['next'] &&
                            CastlingAbility::long($this->board->getCastlingAbility(), $color)
                        ) {
                            if ($clone->play($color, Piece::K.$sq)) {
                                $legal[] = [
                                    Castle::LONG => (new BoardToStr($clone))->create()
                                ];
                            }
                        } elseif ($clone->play($color, Piece::K.$sq)) {
                            $legal[] = [
                                Piece::K.$sq => (new BoardToStr($clone))->create()
                            ];
                        } elseif ($clone->play($color, Piece::K.'x'.$sq)) {
                            $legal[] = [
                                Piece::K.'x'.$sq => (new BoardToStr($clone))->create()
                            ];
                        }
                        break;
                    case Piece::P:
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

    protected function disambiguation(string $color, string $id): array
    {
        $identities = [];
        $clone = unserialize(serialize($this->board));
        foreach ($clone->getPiecesByColor($color) as $piece) {
            foreach ($piece->getSqs() as $sq) {
                switch ($piece->getId()) {
                    case Piece::K:
                        break;
                    case Piece::P:
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
