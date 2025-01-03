<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Extractor
 *
 * Given a PGN movetext in SAN format, this class returns the oscillations of
 * all evaluation features for data analysis purposes.
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
     * Evaluation arrays.
     *
     * @var array
     */
    public array $eval = [];

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
        $this->eval[] = array_fill(0, count($f->names()), 0);

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $items = [];
                    foreach ($f->names() as $val) {
                        $item = EvalArray::add(EvalFactory::create($f, $val, $this->board));
                        $items[] =  $item[Color::W] - $item[Color::B];
                    }
                    $result[] = $items;
                    $eval = EvalArray::normalize(-1, 1, $items);
                    $this->eval[] = $eval;
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
