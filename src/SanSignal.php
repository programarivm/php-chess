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
     * The balanced result.
     *
     * @var array
     */
    public array $balance = [];

    /**
     * Normalized signal in the time domain.
     *
     * @var array
     */
    public array $time = [];

    /**
     * Normalized signal in the spectrum domain.
     *
     * @var array
     */
    public array $spectrum = [];

    /**
     * Spectrum domain components.
     *
     * @var array
     */
    public array $spectrumComponent = [];

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

        $result = [];

        $result[] = array_fill(0, count($function->names()), 0);
        $this->spectrumComponent[] = array_fill(0, count($function->names()), 0);
        $this->spectrum[] = 0;

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $items = [];
                    foreach ($function->names() as $val) {
                        $item = $this->item(EvalFactory::create(
                            $function,
                            $val,
                            $this->board
                        ));
                        $items[] =  $item[Color::W] - $item[Color::B];
                    }
                    $result[] = $items;
                    $spectrumComponent = $this->normalize(-1, 1, $items);
                    $this->spectrumComponent[] = $spectrumComponent;
                    $this->spectrum[] = round(array_sum($spectrumComponent), 2);
                }
            }
        }

        for ($i = 0; $i < count($result[0]); $i++) {
            $this->balance[$i] = $this->normalize(-1, 1, array_column($result, $i));
        }

        for ($i = 0; $i < count($this->balance[0]); $i++) {
            $this->time[$i] = round(array_sum(array_column($this->balance, $i)), 2);
        }
    }
}
