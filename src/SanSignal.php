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
 * A signal encoding the continuous oscillations of a chessboard.
 */
class SanSignal extends SanPlay
{
    use SanTrait;

    /**
     * Unormalized signal.
     *
     * @var array
     */
    public array $unnormalized = [];

    /**
     * Normalized signal.
     *
     * @var array
     */
    public array $normalized = [];

    /**
     * Signal components.
     *
     * @var array
     */
    public array $component = [];

    /**
     * @param \Chess\Function\AbstractFunction $function
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $function,
        string $movetext,
        AbstractBoard $board
    ) {
        parent::__construct($movetext, $board);

        $this->result[] = array_fill(0, count($function->names()), 0);
        $items = [];

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    foreach ($function->names() as $val) {
                        $item = $this->item(EvalFactory::create(
                            $function,
                            $val,
                            $this->board
                        ));
                        $items[] =  $item[Color::W] - $item[Color::B];
                    }
                    $this->result[] = $items;
                    $items = [];
                }
            }
        }

        for ($i = 0; $i < count($this->result[0]); $i++) {
            $this->component[$i] = array_column($this->result, $i);
            $this->balance[$i] = $this->normalize(-1, 1, $this->component[$i]);
        }

        for ($i = 0; $i < count($this->component[0]); $i++) {
            $this->unnormalized[$i] = round(array_sum(array_column($this->component, $i)), 2);
            $this->normalized[$i] = round(array_sum(array_column($this->balance, $i)), 2);
        }
    }
}
