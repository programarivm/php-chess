<?php

namespace Chess\ML\Supervised\Classification;

use Chess\Board;
use Chess\HeuristicPicture;
use Chess\ML\Supervised\AbstractLinearCombinationPredictor;
use Chess\ML\Supervised\Classification\LinearCombinationLabeller;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Rubix\ML\Datasets\Unlabeled;

/**
 * LinearCombinationPredictor
 *
 * Predicts the best possible move.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class LinearCombinationPredictor extends AbstractLinearCombinationPredictor
{
    /**
     * Returns the best possible move.
     *
     * @return string
     */
    public function predict(): string
    {
        $color = $this->board->getTurn();
        foreach ($this->board->getPossibleMoves() as $possibleMove) {
            $clone = unserialize(serialize($this->board));
            $clone->play(Convert::toStdObj($this->board->getTurn(), $possibleMove));
            $this->result[] = [ $possibleMove => $this->evaluate($clone) ];
        }

        $prediction = $this->prediction();

        $found = $this->sort($color)->find($prediction);

        return $found;
    }

    /**
     * Evaluates the chess position which results from playing the current PGN movetext.
     *
     * @return array
     */
    protected function evaluate(Board $clone): array
    {
        $balance = (new HeuristicPicture($clone->getMovetext()))->take()->getBalance();
        $end = end($balance);
        $color = $this->board->getTurn();
        $label = (new LinearCombinationLabeller($this->permutations))->label($end)[$color];

        return [
            'label' => $label,
            'linear_combination' => $this->combine($end, $label),
            'heuristic_eval' => (new HeuristicPicture($clone->getMovetext()))->evaluate(),
        ];
    }

    /**
     * Sorts all possible moves by their linear combination value.
     *
     * @return \Chess\ML\Supervised\Classification\LinearCombinationPredictor
     */

    protected function sort(string $color): LinearCombinationPredictor
    {
        usort($this->result, function ($a, $b) use ($color) {
            if ($color === Symbol::WHITE) {
                $current =
                    (current($b)['heuristic_eval']['b'] - current($b)['heuristic_eval']['w'] <=>
                        current($a)['heuristic_eval']['b'] - current($a)['heuristic_eval']['w']) * 10 +
                    (current($b)['linear_combination'] <=> current($a)['linear_combination']);
            } else {
                $current =
                    (current($a)['heuristic_eval']['w'] - current($a)['heuristic_eval']['b'] <=>
                        current($b)['heuristic_eval']['w'] - current($b)['heuristic_eval']['b']) * 10 +
                    (current($a)['linear_combination'] <=> current($b)['linear_combination']);
            }

            return $current;
        });

        return $this;
    }

    /**
     * Finds the move to be made by matching the best possible move with the predicted label.
     *
     * @return string
     */
    protected function find($prediction): string
    {
        foreach ($this->result as $key => $val) {
            $current = current($val);
            if ($prediction === $current['label']) {
                return key($this->result[$key]);
            }
        }

        return key($this->result[0]);
    }

    /**
     * Predicts the type of chess move (which is an interger label) that should be made
     * after playing the current PGN movetext.
     *
     * @return int
     */
    protected function prediction(): int
    {
        $balance = (new HeuristicPicture($this->board->getMovetext()))->take()->getBalance();
        $end = end($balance);
        $dataset = new Unlabeled([$end]);

        return current($this->estimator->predict($dataset));
    }
}
