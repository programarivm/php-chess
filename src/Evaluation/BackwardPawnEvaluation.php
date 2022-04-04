<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SpaceEvaluation;
use Chess\PGN\Symbol;

/**
 * Backward Pawn
 *
 * @author Boas Falke
 * @license GPL
 */
class BackwardPawnEvaluation extends AbstractEvaluation implements InverseEvaluationInterface
{
    const NAME = 'backward_pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        $captureSquares = [];
        $nextMoves = [];
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Symbol::PAWN) {
                $captureSquares[] = [
                    'color' => $piece->getColor(),
                    'captureSquares' => $piece->getCaptureSquares(),
                ];

                //Only check for movable pawns and their next possible square
                if (0 === count($piece->getSquares()) || !str_contains($piece->getSquares()[0], $piece->getFile())) {
                    continue;
                }
                $nextMoves[] = [
                    'color' => $piece->getColor(),
                    'nextSquare' => $piece->getSquares()[0],
                    'nextSquareDefendedByPawn' => false,
                ];
            }
        }

        if (0 === count($nextMoves) || 0 === count($captureSquares)) {
            return $this->result;
        }

        $nextMoves = array_map(static function ($nextMove) use ($captureSquares) {
            foreach ($captureSquares as $captureSquare) {
                if (
                    $nextMove['color'] === $captureSquare['color'] &&
                    in_array($nextMove['nextSquare'], $captureSquare['captureSquares'], true)
                )
                {
                    $nextMove['nextSquareDefendedByPawn'] = true;
                    return $nextMove;
                }
            }
            return $nextMove;
        }, $nextMoves);

        foreach ($nextMoves as $nextMove) {
            foreach ($captureSquares as $captureSquare) {
                if ($nextMove['nextSquareDefendedByPawn']) {
                    continue;
                }
                if (
                    $nextMove['color'] !== $captureSquare['color'] &&
                        in_array($nextMove['nextSquare'], $captureSquare['captureSquares'], true)
                ) {
                    ++$this->result[$nextMove['color']];
                }
            }
        }
        return $this->result;
    }
}
