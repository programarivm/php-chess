<?php

namespace Chess\ML\Supervised\Classification;

use Chess\Board;
use Chess\Heuristics;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\ML\Supervised\AbstractPredictor;
use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;

class PermutationPredictor extends AbstractPredictor
{
    public function __construct(Board $board, PersistentModel $estimator)
    {
        parent::__construct($board, $estimator);

        $this->permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [4, 28],
                count((new Heuristics(''))->getDimensions()),
                100
            );
    }

    protected function eval(Board $clone): array
    {
        $balance = (new Heuristics($clone->getMovetext(), $clone))->getBalance();

        $dataset = new Unlabeled($balance);

        $end = end($balance);

        $label = (new PermutationLabeller($this->permutations))
            ->label($end)[$this->board->getTurn()];

        return [
            'label' => $label,
            'prediction' => current($this->estimator->predict($dataset)),
        ];
    }

    protected function find(): string
    {
        foreach ($this->result as $key => $val) {
            $current = current($val);
            if ($current['label'] === $current['prediction']) {
                return key($this->result[$key]);
            }
        }

        return key($this->result[0]);
    }
}
