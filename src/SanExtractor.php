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
 * Oscillations of evaluation features in the heuristic domain.
 */
class SanExtractor extends SanPlay
{
    /**
     * Mean.
     *
     * @var array
     */
    public array $mean = [
        0,
    ];

    /**
     * Standard deviation.
     *
     * @var array
     */
    public array $sd = [
        0,
    ];

    /**
     * Heuristic components.
     *
     * @var array
     */
    public array $heuristic = [];

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
        $this->heuristic[] = array_fill(0, count($f->names()), 0);

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $items = [];
                    foreach ($f->names() as $val) {
                        $item = EvalArray::add(EvalFactory::create($f, $val, $this->board));
                        $items[] =  $item[Color::W] - $item[Color::B];
                    }
                    $result[] = $items;
                    $heuristic = EvalArray::normalize(-1, 1, $items);
                    $this->heuristic[] = $heuristic;
                    $mean = EvalArray::mean($f, $this->board);
                    $this->mean[] = $mean;
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
    }
}
