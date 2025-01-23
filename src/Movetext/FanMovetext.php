<?php

namespace Chess\Movetext;

use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Piece;

class FanMovetext extends AbstractMovetext
{
    public array $metadata;

    public SanMovetext $sanMovetext;

    public function __construct(Move $move, string $movetext)
    {
        $this->sanMovetext = new SanMovetext($move, $this->toSan($movetext));
        $this->move = $this->sanMovetext->move;
        $this->movetext = $this->sanMovetext->movetext;
        $this->moves = array_map(function ($move) {
            return $this->toFan($move);
        }, $this->sanMovetext->moves);
        $this->metadata = [
            'firstMove' => $this->toFan($this->sanMovetext->metadata['firstMove']),
            'lastMove' => $this->toFan($this->sanMovetext->metadata['lastMove']),
            'turn' => $this->sanMovetext->metadata['turn'],
        ];
    }

    public function validate(): string
    {
        $this->sanMovetext->validate();

        return $this->toFan($this->sanMovetext->validated);
    }

    public function filtered($comments = true, $nags = true): string
    {
        $filtered = $this->sanMovetext->filtered($comments, $nags);

        return $this->toFan($filtered);
    }

    private function toFan(string &$movetext): string
    {
        $this->replace(Piece::R, '♖', $movetext)
            ->replace(Piece::N, '♘', $movetext)
            ->replace(Piece::B, '♗', $movetext)
            ->replace(Piece::Q, '♕', $movetext)
            ->replace(Piece::K, '♔', $movetext);

        return $movetext;
    }

    private function toSan(string $movetext): string
    {
        return str_replace(
            ['♖', '♘', '♗', '♕', '♔'],
            [Piece::R, Piece:: N, Piece::B, Piece::Q, Piece:: K],
            $movetext
        );
    }

    private function replace($letter, $unicode, &$movetext): FanMovetext
    {

        preg_match_all('/' . Move::PIECE . '/', $movetext, $a);
        preg_match_all('/' . Move::PIECE_CAPTURES . '/', $movetext, $b);
        array_map(function ($match) use ($letter, $unicode, &$movetext) {
            $replaced = str_replace($letter, $unicode, $match);
            $movetext = str_replace($match, $replaced, $movetext);
        }, [...$a[0], ...$b[0]]);

        return $this;
    }

    protected function beforeInsert(): FanMovetext
    {
    }

    protected function insert(): void
    {
    }
}
