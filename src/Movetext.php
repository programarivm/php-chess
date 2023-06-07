<?php

namespace Chess;

use Chess\Exception\MovetextException;
use Chess\Variant\Classical\PGN\AN\Termination;
use Chess\Variant\Classical\PGN\Move;

/**
 * Movetext.
 *
 * @license GPL
 */
class Movetext
{
    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    private Move $move;

    /**
     * Movetext.
     *
     * @var object
     */
    private object $movetext;

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $text
     */
    public function __construct(Move $move, string $text)
    {
        $this->move = $move;

        $this->movetext = (object) [
            'n' => [],
            'moves' => [],
        ];

        $this->filter($text);
    }

    /**
     * Returns the movetext.
     *
     * @return object
     */
    public function getMovetext(): object
    {
        return $this->movetext;
    }

    /**
     * Validation.
     *
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->movetext->moves as $move) {
            if ($move !== '...') {
                $this->move->validate($move);
            }
        }

        return $this->toString();
    }

    /**
     * Converts the movetext data structure to a string.
     *
     * @return string
     */
    protected function toString(): string
    {
        $text = '';
        $index = 0;

        if (isset($this->movetext->moves[0])) {
            if ($this->movetext->moves[0] === '...') {
                $text = "1...{$this->movetext->moves[1]} ";
                $index = 2;
            }
        }

        for ($i = $index; $i < count($this->movetext->moves); $i++) {
            if ($i % 2 === 0) {
                $text .= (($i / 2) + 1) . ".{$this->movetext->moves[$i]}";
            } else {
                $text .= " {$this->movetext->moves[$i]} ";
            }
        }

        return trim($text);
    }

    /**
     * Filters the given text for further processing.
     *
     * @param string $text
     */
    protected function filter(string $text): void
    {
        // remove PGN symbols
        $text = str_replace(Termination::values(), '', $text);

        // remove comments
        $text = preg_replace("/\{[^)]+\}/", '', $text);
        $text = preg_replace("/\([^)]+\)/", '', $text);

        // replace FIDE notation with PGN notation
        $text = str_replace('0-0', 'O-O', $text);
        $text = str_replace('0-0-0', 'O-O-O', $text);

        // remove spaces between dots
        $text = preg_replace('/\s+\./', '.', $text);

        $this->fill($text);
    }

    /**
     * Fills the movetext data structure with data.
     *
     * @param string $text
     */
    protected function fill(string $text): void
    {
        $moves = explode(' ', $text);
        foreach ($moves as $key => $val) {
            if ($key === 0) {
                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode('...', $val);
                    $this->movetext->n[] = $exploded[0];
                    $this->movetext->moves[] = '...';
                    $this->movetext->moves[] = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $exploded = explode('.', $val);
                    $this->movetext->n[] = $exploded[0];
                    $this->movetext->moves[] = $exploded[1];
                }
            } else {
                if (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $exploded = explode('.', $val);
                    $this->movetext->n[] = $exploded[0];
                    $this->movetext->moves[] = $exploded[1];
                } else {
                    $this->movetext->moves[] = $val;
                }
            }
        }
    }

    /**
     * Returns an array representing the movetext as a sequence of moves.
     *
     * e.g. 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5
     *
     * Array
     * (
     *  [0] => 1.d4 Nf6
     *  [1] => 1.d4 Nf6 2.Nf3 e6
     *  [2] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+
     *  [3] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O
     *  [4] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7
     *  [5] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6
     *  [6] => 1.d4 Nf6 2.Nf3 e6 3.c4 Bb4+ 4.Nbd2 O-O 5.a3 Be7 6.e4 d6 7.Bd3 c5
     * )
     *
     * @return array
     */
    public function sequence(): array
    {
        $sequence = [];
        for ($i = 0; $i < count($this->movetext->n); $i++) {
            $j = 2 * $i;
            if (isset($this->movetext->moves[$j+1])) {
                $item = end($sequence) .
                    " {$this->movetext->n[$i]}.{$this->movetext->moves[$j]} {$this->movetext->moves[$j+1]}";
                $sequence[] = trim($item);
            }
        }

        return $sequence;
    }
}
