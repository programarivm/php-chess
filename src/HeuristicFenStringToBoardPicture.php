<?php

namespace Chess;

use Chess\Board;
use Chess\Evaluation\AttackEvaluation;
use Chess\Evaluation\CenterEvaluation;
use Chess\Evaluation\ConnectivityEvaluation;
use Chess\Evaluation\KingSafetyEvaluation;
use Chess\Evaluation\MaterialEvaluation;
use Chess\Evaluation\PressureEvaluation;
use Chess\Evaluation\SpaceEvaluation;
use Chess\Evaluation\TacticsEvaluation;
use Chess\PGN\Symbol;

class HeuristicFenStringToBoardPicture
{
    protected $board;

    protected $dimensions = [
        MaterialEvaluation::class => 34,
        CenterEvaluation::class => 13,
        ConnectivityEvaluation::class => 13,
        SpaceEvaluation::class => 8,
        PressureEvaluation::class => 8,
        KingSafetyEvaluation::class => 8,
        TacticsEvaluation::class => 8,
        AttackEvaluation::class => 8,
    ];

    protected $picture = [
        Symbol::WHITE => [],
        Symbol::BLACK => [],
    ];

    protected $balance = [];

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function setDimensions(array $dimensions): HeuristicFenStringToBoardPicture
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getPicture(): array
    {
        return $this->picture;
    }

    public function getBalance(): array
    {
        return $this->balance;
    }

    /**
     * Takes a normalized, balanced heuristic picture.
     *
     * @return \Chess\Heuristic\HeuristicFenStringToBoardPicture
     */
    public function take(): HeuristicFenStringToBoardPicture
    {
        $item = [];
        foreach ($this->dimensions as $dimension => $w) {
            $evald = (new $dimension($this->board))->evaluate();
            is_array($evald[Symbol::WHITE])
                ? $item[] = [
                    Symbol::WHITE => count($evald[Symbol::WHITE]),
                    Symbol::BLACK => count($evald[Symbol::BLACK])]
                : $item[] = $evald;
        }
        $this->picture[Symbol::WHITE] = array_column($item, Symbol::WHITE);
        $this->picture[Symbol::BLACK] = array_column($item, Symbol::BLACK);

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
            $result[Symbol::WHITE] += $weights[$i] * $pic[Symbol::WHITE][$i];
            $result[Symbol::BLACK] += $weights[$i] * $pic[Symbol::BLACK][$i];
        }

        $result[Symbol::WHITE] = round($result[Symbol::WHITE], 2);
        $result[Symbol::BLACK] = round($result[Symbol::BLACK], 2);

        return $result;
    }

    protected function normalize(): HeuristicFenStringToBoardPicture
    {
        $normalization = [];

        $values = array_merge(
            $this->picture[Symbol::WHITE],
            $this->picture[Symbol::BLACK]
        );

        $min = min($values);
        $max = max($values);

        for ($i = 0; $i < count($this->dimensions); $i++) {
            if ($max - $min > 0) {
                $normalization[Symbol::WHITE][$i] = round(($this->picture[Symbol::WHITE][$i] - $min) / ($max - $min), 2);
                $normalization[Symbol::BLACK][$i] = round(($this->picture[Symbol::BLACK][$i] - $min) / ($max - $min), 2);
            } elseif ($max == $min) {
                $normalization[Symbol::WHITE][$i] = round(1 / count($values), 2);
                $normalization[Symbol::BLACK][$i] = round(1 / count($values), 2);
            }
        }

        $this->picture = $normalization;

        return $this;
    }

    protected function balance(): HeuristicFenStringToBoardPicture
    {
        foreach ($this->picture[Symbol::WHITE] as $key => $val) {
            $this->balance[$key] = $this->picture[Symbol::WHITE][$key] - $this->picture[Symbol::BLACK][$key];
        }

        return $this;
    }
}
