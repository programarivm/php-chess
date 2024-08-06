<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;

class FanMovetext extends SanMovetext
{
    public array $metadata;

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\PGN\Move $move
     * @param string $movetext
     */
    public function __construct(Move $move, string $movetext)
    {
        parent::__construct($move, $movetext);

        $this->metadata = [
            'firstMove' => $this->firstMove(),
            'lastMove' => $this->lastMove(),
            'turn' => parent::turn(),
        ];

        foreach ($this->moves as $index => $move) {
            $this->moves[$index] = $this->get_figurine_notation($move);
        }
    }

    /**
     * Converts a chess move notation to figurine notation based on the color of the piece.
     *
     * @param string $move The chess move in standard notation (e.g., "Nf4").
     * @return string The chess move in figurine notation (e.g., "♘f4").
     */
    private function get_figurine_notation(string $move): string
    {
        $move = str_replace('R', '♖', $move);
        $move = str_replace('N', '♘', $move);
        $move = str_replace('B', '♗', $move);
        $move = str_replace('Q', '♕', $move);
        $move = str_replace('K', '♔', $move);

        return $move;
    }

    protected function firstMove(): string
    {
        $sanMove = parent::firstMove();
        return $this->get_figurine_notation($sanMove);
    }

    protected function lastMove(): string
    {
        $sanMove = parent::lastMove();
        return $this->get_figurine_notation($sanMove);
    }
}
