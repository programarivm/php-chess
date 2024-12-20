<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Heuristics
 *
 * Continuous oscillations in terms of heuristic evaluation features.
 */
class SanHeuristics extends SanPlay
{
    use SanTrait;

    /**
     * Function.
     *
     * @var \Chess\Function\AbstractFunction
     */
    public AbstractFunction $function;

    /**
     * The name of the evaluation feature.
     *
     * @var string
     */
    public string $name;

    /**
     * Continuous oscillations.
     *
     * @var array
     */
    public array $result = [];

    /**
     * The balanced normalized result.
     *
     * @var array
     */
    public array $balance = [];

    /**
     * @param \Chess\Function\AbstractFunction $function
     * @param string $movetext
     * @param string $name
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $function,
        string $movetext = '',
        string $name = '',
        AbstractBoard $board = null
    ) {
        parent::__construct($movetext, $board);

        $this->function = $function;
        $this->name = $name;

        $this->result[] = $this->item(EvalFactory::create(
            $this->function,
            $this->name,
            $this->board
        ));

        foreach ($this->sanMovetext->moves as $move) {
            if ($move !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $move)) {
                    $this->result[] = $this->item(EvalFactory::create(
                        $this->function,
                        $this->name,
                        $this->board
                    ));
                }
            }
        }

        foreach ($this->result as $result) {
            $this->balance[] = $result[Color::W] - $result[Color::B];
        }

        $this->balance = $this->normalize(-1, 1, $this->balance);
    }
}
