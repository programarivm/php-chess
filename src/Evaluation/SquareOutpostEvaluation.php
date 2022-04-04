<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Piece\Pawn;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;

/**
 * SquareOutpostEvaluation
 *
 * @link https://en.wikipedia.org/wiki/Outpost_(chess)
 */
class SquareOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'square_outpost';

    private $ranks = [3, 4, 5, 6];

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Symbol::PAWN) {
                $captureSquares = $piece->getCaptureSquares();
                if ($piece->getColor() === Symbol::WHITE) {
                    $lFile = chr(ord($piece->getFile()) - 2);
                    $rFile = chr(ord($piece->getFile()) + 2);
                } else {
                    $lFile = chr(ord($piece->getFile()) + 2);
                    $rFile = chr(ord($piece->getFile()) - 2);
                    rsort($captureSquares);
                }
                if (in_array($piece->getSquare()[1], $this->ranks)) {
                    if (!$this->opposition($piece, $piece->getFile())) {
                        if (Validate::file($lFile) && !$this->opposition($piece, $lFile)) {
                            $this->result[$piece->getColor()][] = $captureSquares[0];
                        }
                        if (Validate::file($rFile) && !$this->opposition($piece, $rFile)) {
                            $this->result[$piece->getColor()][] = $captureSquares[0];
                            empty($captureSquares[1]) ?: $this->result[$piece->getColor()][] = $captureSquares[1];
                        }
                    }
                }
            }
        }
        $this->result[Symbol::WHITE] = array_unique($this->result[Symbol::WHITE]);
        $this->result[Symbol::BLACK] = array_unique($this->result[Symbol::BLACK]);
        sort($this->result[Symbol::WHITE]);
        sort($this->result[Symbol::BLACK]);

        return $this->result;
    }

    protected function opposition(Pawn $pawn, string $file): bool
    {
        for ($i = 2; $i < 8; $i++) {
            if ($piece = $this->board->getPieceBySq($file.$i)) {
                if ($piece->getId() === Symbol::PAWN) {
                    if ($pawn->getColor() === Symbol::WHITE) {
                        if ($pawn->getSquare()[1] + 2 <= $piece->getSquare()[1]) {
                            return true;
                        }
                    } else {
                        if ($pawn->getSquare()[1] - 2 >= $piece->getSquare()[1]) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
