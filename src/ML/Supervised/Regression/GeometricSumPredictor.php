<?php

namespace Chess\ML\Supervised\Regression;

use Chess\Board;
use Chess\Heuristics;
use Chess\ML\Supervised\AbstractPredictor;
use Rubix\ML\Datasets\Unlabeled;

class PermutationPredictor extends AbstractPredictor
{
    protected function eval(Board $clone): array
    {
        $balance = (new Heuristics($clone->getMovetext(), $clone))->getBalance();

        $dataset = new Unlabeled($balance);

        $end = end($balance);

        $label = (new GeometricSumLabeller())
            ->label($end);

        return [
            'label' => $label,
            'prediction' => current($this->estimator->predict($dataset)),
        ];
    }

    protected function find(): string
    {
        $diffs = [];
        foreach ($this->result as $key => $val) {
            $current = current($val);
            $diffs[$key] = abs($current['label'] - $current['prediction']);
        }
        $mins = array_keys($diffs, min($diffs));
        shuffle($mins);
        $min = $mins[0];

        return key($this->result[$min]);
    }
}
