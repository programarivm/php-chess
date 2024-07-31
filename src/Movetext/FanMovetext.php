<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

class FanMovetext extends AbstractMovetext
{
    private const WHITE_PIECES = [
        'N' => '♘', // White Knight
        'B' => '♗', // White Bishop
        'R' => '♖', // White Rook
        'Q' => '♕', // White Queen
        'K' => '♔', // White King
    ];
    
    private const BLACK_PIECES = [
        'N' => '♞', // Black Knight
        'B' => '♝', // Black Bishop
        'R' => '♜', // Black Rook
        'Q' => '♛', // Black Queen
        'K' => '♚', // Black King
    ];

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
            'turn' => $this->turn(),
        ];

    }

    /**
     * Converts a chess move notation to figurine notation based on the color of the piece.
     *
     * @param string $move The chess move in standard notation (e.g., "Nf4").
     * @param string $color The color of the chess piece (w or b).
     * @return string The chess move in figurine notation (e.g., "♘f4" for White or "♞f4" for Black).
     */
    private function get_figurine_notation(string $move, string $color): string
    {
        $pieces = ($color === Color::W) ? self::WHITE_PIECES : self::BLACK_PIECES;

        $pieceType = $move[0];
        $moveSquare = substr($move, 1);

        $figurine = $pieces[$pieceType] ?? $pieceType;

        return $figurine.$moveSquare;
    }

    /**
     * Before inserting elements into the array of moves.
     *
     * @return \Chess\Movetext\FanMovetext
     */
    protected function beforeInsert(): FanMovetext
    {
        $str = preg_replace('(\{.*?\})', '', $this->filtered());
        $str = preg_replace('/\s+/', ' ', $str);

        $this->validated = trim($str);

        return $this;
    }

    /**
     * Insert elements into the array of moves.
     *
     */
    protected function insert(): void
    {
        foreach (explode(' ', $this->validated) as $key => $val) {
            if (!NagMovetext::glyph($val)) {
                $value = $val;
                $color = Color::B;

                if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                    $exploded = explode(Move::ELLIPSIS, $val);
                    $value = $exploded[1];
                } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                    $value = explode('.', $val)[1];
                    $color = Color::W;
                }
                $this->moves[] = self::get_figurine_notation($value, $color);
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }

    protected function turn(): string
    {
        $exploded = explode(' ', $this->validated);
        $last = end($exploded);
        if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $last)) {
            return Color::W;
        } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $last)) {
            return Color::B;
        }

        return Color::W;
    }

    protected function firstMove(): string
    {
        $exploded = explode(' ', $this->validated);
        $moveColor = $this->turn() == Color::W ? Color::B : Color::W;
        return self::get_figurine_notation($exploded[0], $moveColor);
    }

    protected function lastMove(): string
    {
        $exploded = explode(' ', $this->validated);
        $last = end($exploded);
        if (!str_contains($last, '.')) {
            $nextToLast = prev($exploded);
            return "{$nextToLast} {$last}";
        }

        $moveColor = $this->turn() == Color::W ? Color::B : Color::W;
        return self::get_figurine_notation($exploded[0], $moveColor);
    }

    /**
     * Syntactically validated movetext.
     *
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    public function validate(): string
    {
        foreach ($this->moves as $move) {
            $this->move->validate($move);
        }

        return $this->validated;
    }
}
