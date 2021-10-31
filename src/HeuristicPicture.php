<?php

namespace Chess;

use Chess\Evaluation\AttackEvaluation;
use Chess\Evaluation\BackwardPawnEvaluation;
use Chess\Evaluation\CenterEvaluation;
use Chess\Evaluation\ConnectivityEvaluation;
use Chess\Evaluation\IsolatedPawnEvaluation;
use Chess\Evaluation\KingSafetyEvaluation;
use Chess\Evaluation\MaterialEvaluation;
use Chess\Evaluation\PressureEvaluation;
use Chess\Evaluation\SpaceEvaluation;
use Chess\Evaluation\SquareEvaluation;
use Chess\Evaluation\TacticsEvaluation;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Evaluation\DoubledPawnEvaluation;
use Chess\Evaluation\PassedPawnEvaluation;

class HeuristicPicture extends Player
{
    protected $dimensions = [
        MaterialEvaluation::class => 21,
        CenterEvaluation::class => 21,
        ConnectivityEvaluation::class => 13,
        SpaceEvaluation::class => 5,
        PressureEvaluation::class => 5,
        KingSafetyEvaluation::class => 5,
        TacticsEvaluation::class => 5,
        AttackEvaluation::class => 5,
        DoubledPawnEvaluation::class => 5,
        PassedPawnEvaluation::class => 5,
        IsolatedPawnEvaluation::class => 5,
        BackwardPawnEvaluation::class => 5,
    ];

    protected $picture = [];

    protected $balance = [];

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function setDimensions(array $dimensions): HeuristicPicture
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getPicture(): array
    {
        return $this->picture;
    }

    public function end(): array
    {
        return [
            Symbol::WHITE => end($this->picture[Symbol::WHITE]),
            Symbol::BLACK => end($this->picture[Symbol::BLACK]),
        ];
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Takes a normalized, balanced heuristic picture.
     *
     * @return \Chess\Heuristic\HeuristicPicture
     */
    public function take(): HeuristicPicture
    {
        foreach ($this->moves as $move) {
            $this->board->play(Convert::toStdObj(Symbol::WHITE, $move[0]));
            if (isset($move[1])) {
                $this->board->play(Convert::toStdObj(Symbol::BLACK, $move[1]));
            }
            $item = [];
            foreach ($this->dimensions as $dimension => $w) {
                $evald = (new $dimension($this->board))->evaluate();
                is_array($evald[Symbol::WHITE])
                    ? $item[] = [
                        Symbol::WHITE => count($evald[Symbol::WHITE]),
                        Symbol::BLACK => count($evald[Symbol::BLACK])]
                    : $item[] = $evald;
            }
            $this->picture[Symbol::WHITE][] = array_column($item, Symbol::WHITE);
            $this->picture[Symbol::BLACK][] = array_column($item, Symbol::BLACK);
        }

        $this->normalize()->balance();

        return $this;
    }

    public function evaluate(): array
    {
        $result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $weights = array_values($this->getDimensions());

        $pic = $this->take()->getPicture();

        for ($i = 0; $i < count($this->getDimensions()); $i++) {
            $result[Symbol::WHITE] += $weights[$i] * end($pic[Symbol::WHITE])[$i];
            $result[Symbol::BLACK] += $weights[$i] * end($pic[Symbol::BLACK])[$i];
        }

        $result[Symbol::WHITE] = round($result[Symbol::WHITE], 2);
        $result[Symbol::BLACK] = round($result[Symbol::BLACK], 2);

        return $result;
    }

    protected function normalize(): HeuristicPicture
    {
        $normalization = [];

        if (count($this->board->getHistory()) >= 2) {
            for ($i = 0; $i < count($this->dimensions); $i++) {
                $values = array_merge(
                    array_column($this->picture[Symbol::WHITE], $i),
                    array_column($this->picture[Symbol::BLACK], $i)
                );
                $min = round(min($values), 2);
                $max = round(max($values), 2);
                for ($j = 0; $j < count($this->picture[Symbol::WHITE]); $j++) {
                    if ($max - $min > 0) {
                        $normalization[Symbol::WHITE][$j][$i] = round(($this->picture[Symbol::WHITE][$j][$i] - $min) / ($max - $min), 2);
                        $normalization[Symbol::BLACK][$j][$i] = round(($this->picture[Symbol::BLACK][$j][$i] - $min) / ($max - $min), 2);
                    } elseif ($max == $min) {
                        $normalization[Symbol::WHITE][$j][$i] = 0;
                        $normalization[Symbol::BLACK][$j][$i] = 0;
                    }
                }
            }
        } else {
            $normalization[Symbol::WHITE][] = $normalization[Symbol::BLACK][] = array_fill(0, count($this->dimensions), 0);
        }

        $this->picture = $normalization;

        return $this;
    }

    protected function balance(): HeuristicPicture
    {
        foreach ($this->picture[Symbol::WHITE] as $i => $color) {
            foreach ($color as $j => $val) {
                $this->balance[$i][$j] = $this->picture[Symbol::WHITE][$i][$j] - $this->picture[Symbol::BLACK][$i][$j];
            }
        }

        return $this;
    }
}
