<?php

namespace Chess\FEN;

use Chess\Castling\Rule as CastlingRule;
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
                $identity = $piece->getIdentity();
                $position = $piece->getPosition();
                switch ($identity) {
                    case Symbol::KING:
                        $rule = CastlingRule::color($color)[Symbol::KING];
                        if ($sq === $rule[Symbol::CASTLING_SHORT]['position']['next'] &&
                            $this->board->getCastling()[$color][Symbol::CASTLING_SHORT]
                        ) {
                            if ($clone->play($color, Symbol::KING.$sq)) {
                                $legal[] = [
                                    Symbol::CASTLING_SHORT => (new BoardToString($clone))->create()
                                ];
                            }
                        } elseif ($sq === $rule[Symbol::CASTLING_LONG]['position']['next'] &&
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
                        if (in_array($sq, $this->disambiguation($color, $identity))) {
                            if ($clone->play($color, $identity.$position.$sq)) {
                                $legal[] = [
                                    $identity.$position.$sq => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$identity}{$position}x$sq")) {
                                $legal[] = [
                                    "{$identity}{$position}x$sq" => (new BoardToString($clone))->create()
                                ];
                            }
                        } else {
                            if ($clone->play($color, $identity.$sq)) {
                                $legal[] = [
                                    $identity.$sq => (new BoardToString($clone))->create()
                                ];
                            } elseif ($clone->play($color, "{$identity}x{$sq}")) {
                                $legal[] = [
                                    "{$identity}x{$sq}" => (new BoardToString($clone))->create()
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
            foreach ($piece->getSquares() as $sq) {
                switch ($piece->getIdentity()) {
                    case Symbol::KING:
                        break;
                    case Symbol::PAWN:
                        break;
                    default:
                        $identities[$piece->getIdentity()][$piece->getPosition()][] = $sq;
                        break;
                }
            }
        }
        $vals = array_merge(...array_values($identities[$identity]));
        $duplicates = array_diff_assoc($vals, array_unique($vals));

        return $duplicates;
    }
}
