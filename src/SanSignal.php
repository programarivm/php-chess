<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Signal
 *
 * Continuous oscillations of evaluation features.
 */
class SanSignal extends SanPlay
{
    /**
     * Normalization of the time domain.
     *
     * @var array
     */
    public array $time = [];

    /**
     * Time domain components.
     *
     * @var array
     */
    public array $timeComponent = [];

    /**
     * Standard deviation.
     *
     * @var array
     */
    public array $sd = [
        0,
    ];

    /**
     * Heuristic domain components.
     *
     * @var array
     */
    public array $heuristicComponent = [];

    /**
     * @param \Chess\Function\AbstractFunction $f
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $f,
        string $movetext,
        AbstractBoard $board
    ) {
        parent::__construct($movetext, $board);

        $result[] = array_fill(0, count($f->names()), 0);
        $this->heuristicComponent[] = array_fill(0, count($f->names()), 0);

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $items = [];
                    foreach ($f->names() as $val) {
                        $item = EvalArray::add(EvalFactory::create($f, $val, $this->board));
                        $items[] =  $item[Color::W] - $item[Color::B];
                    }
                    $result[] = $items;
                    $heuristicComponent = EvalArray::normalize(-1, 1, $items);
                    $this->heuristicComponent[] = $heuristicComponent;
                    $mean = EvalArray::mean($f, $this->board);
                    if ($mean > 0) {
                        $this->sd[] = EvalArray::sd($f, $this->board);
                    } elseif ($mean < 0) {
                        $this->sd[] = EvalArray::sd($f, $this->board) * -1;
                    } else {
                        $this->sd[] = 0;
                    }
                }
            }
        }

        for ($i = 0; $i < count($result[0]); $i++) {
            $this->timeComponent[$i] = EvalArray::normalize(-1, 1, array_column($result, $i));
        }

        for ($i = 0; $i < count($this->timeComponent[0]); $i++) {
            $this->time[$i] = round(array_sum(array_column($this->timeComponent, $i)), 2);
        }
    }
}
