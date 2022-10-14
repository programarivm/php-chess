<?php

namespace Chess;

use Chess\Exception\MovetextException;
use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\AN\Termination;
use Chess\Variant\Classical\PGN\Move;

/**
 * Movetext.
 *
 * Movetext provides with functionality to parse and process a PGN movetext.
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

    public function __construct(string $text)
    {
        $this->move = new Move();

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
        if (!$this->isOrdered()) {
            throw new MovetextException;
        }

        foreach ($this->movetext->moves as $move) {
            $this->move->validate($move);
        }

        return $this->join();
    }

    /**
     * Concatenates the moves to form a string.
     *
     * @return string
     */
    protected function join(): string
    {
        $text = '';
        foreach ($this->movetext->moves as $key => $val) {
            $key % 2 === 0
                ? $text .= (($key / 2) + 1) . ".{$this->movetext->moves[$key]}"
                : $text .= " {$this->movetext->moves[$key]} ";
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
        // remove the PGN symbols found in the filter
        $text = str_replace(Termination::values(), '', $text);

        // remove comments
        $text = preg_replace("/\{[^)]+\}/", '', $text);
        $text = preg_replace("/\([^)]+\)/", '', $text);

        // replace fide long castle
        $text = preg_replace("/0-0/", 'O-O', $text);

        // replace fide short castle
        $text = preg_replace("/0-0-0/", 'O-O-O', $text);

        // remove spaces between dots
        $text = preg_replace('/\s+\./', '.', $text);

        // build the array of moves
        foreach ($moves = explode(' ', $text) as $move) {
            if (preg_match('/^[1-9][0-9]*\.(.*)$/', $move)) {
                $exploded = explode('.', $move);
                $this->movetext->n[] = $exploded[0];
                $this->movetext->moves[] = $exploded[1];
            } else {
                $this->movetext->moves[] = $move;
            }
        }

        $this->movetext->moves = array_values(array_filter($this->movetext->moves));
    }

    /**
     * Finds out if the movetext is ordered.
     *
     * @return bool
     */
    protected function isOrdered(): bool
    {
        $isOrdered = 1;
        for ($i = 0; $i < count($this->movetext->n); $i++) {
            $isOrdered *= (int) $this->movetext->n[$i] == $i + 1;
        }

        return (bool) $isOrdered;
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
