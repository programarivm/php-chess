<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Attack evaluation.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class AttackEval extends AbstractEval
{
    const NAME = 'Attack';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            switch ($piece->getId()) {
                case Piece::K:
                    // TODO ...
                    break;
                case Piece::P:
                    foreach ($piece->getCaptureSqs() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Piece::K && self::$value[Piece::P] < self::$value[$id]) {
                                    $this->result[$piece->getColor()] += self::$value[$id] - self::$value[Piece::P];
                                    $this->explain($piece, $item);
                                }
                            }
                        }
                    }
                    break;
                default:
                    foreach ($piece->sqs() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Piece::K && self::$value[$piece->getId()] < self::$value[$id]) {
                                    $this->result[$piece->getColor()] += self::$value[$id] - self::$value[$piece->getId()];
                                    $this->explain($piece, $item);
                                }
                            }
                        }
                    }
                    break;
            }
        }
    }

    private function explain($subject, $target = null)
    {
        $subjectPhrase = PiecePhrase::predictable($subject);
        $targetPhrase = PiecePhrase::predictable($target);
        $this->phrases[] = "{$subjectPhrase} is attacking {$targetPhrase}.";

        return $this->phrases;
    }
}
