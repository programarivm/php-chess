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
        $component = [];

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$board->play($board->turn, $val)) {
                    throw new MediaException();
                }
                foreach ($function->names() as $val) {
                    $item = $this->item(EvalFactory::create(
                        $function,
                        $val,
                        $board
                    ));
                    $diff = $item[Color::W] - $item[Color::B];
                    $component[] =  $diff;
                }
                $this->result[] = $component;
                $component = [];
            }
        }

        for ($i = 0; $i < count($this->result[0]); $i++) {
            $this->balance[$i] = $this->normalize(-1, 1, array_column($this->result, $i));
        }
    }
}
