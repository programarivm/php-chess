<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Backward P
 *
 * @author Boas Falke
 * @license GPL
 */
class BackwardPawnEval extends AbstractEval implements InverseEvalInterface
{
    public const NAME = 'Backward pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
        $captureSquares = [];
        $nextMoves = [];
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                $captureSquares[] = [
                    'color' => $piece->getColor(),
                    'captureSquares' => $piece->getCaptureSqs(),
                ];

                //Only check for movable pawns and their next possible square
                if (0 === count($piece->sqs()) || !str_contains($piece->sqs()[0], $piece->getFile())) {
                    continue;
                }
                $nextMoves[] = [
                    'color' => $piece->getColor(),
                    'nextSquare' => $piece->sqs()[0],
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
