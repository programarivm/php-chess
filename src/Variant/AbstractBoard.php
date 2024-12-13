<?php

namespace Chess\Variant;

use Chess\FenToBoardFactory;
use Chess\Eval\SpaceEval;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Piece\K;
use Chess\Variant\Classical\Piece\P;
use Chess\Variant\Classical\PGN\Move;

abstract class AbstractBoard extends \SplObjectStorage
{
    use AbstractBoardObserverTrait;

    /**
     * Current player's turn.
     *
     * @var string
     */
    public string $turn = '';

    /**
     * History.
     *
     * @var array
     */
    public array $history = [];

    /**
     * Color.
     *
     * @var \Chess\Variant\AbstractNotation
     */
    public AbstractNotation $color;

    /**
     * Castling rule.
     *
     * @var \Chess\Variant\AbstractNotation
     */
    public ?AbstractNotation $castlingRule = null;

    /**
     * Square.
     *
     * @var \Chess\Variant\AbstractNotation
     */
    public AbstractNotation $square;

    /**
     * Move.
     *
     * @var \Chess\Variant\AbstractNotation
     */
    public AbstractNotation $move;

    /**
     * Castling ability.
     *
     * @var string
     */
    public string $castlingAbility = '-';

    /**
     * Variant.
     *
     * @var string
     */
    public string $variant = '';

    /**
     * Start FEN position.
     *
     * @var string
     */
    public string $startFen = '';

    /**
     * Space evaluation.
     *
     * @var array
     */
    public array $spaceEval;

    /**
     * Count squares.
     *
     * @var array
     */
    public array $sqCount;

    /**
     * Picks a piece from the board.
     *
     * @param array $move
     * @return array
     */
    protected function pickPiece(array $move): array
    {
        $pieces = [];
        foreach ($this->pieces($move['color']) as $piece) {
            if ($piece->id === $move['id']) {
                if (strstr($piece->sq, $move['from'])) {
                    $piece->move = $move;
                    $pieces[] = $piece;
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns true if the move is ambiguous.
     *
     * @param array $move
     * @param array $pieces
     * @return bool
     */
    protected function isAmbiguous(array $move, array $pieces): bool
    {
        if (str_contains($move['case'], 'x')) {
            if ($move['id'] === Piece::P) {
                $enPassant = $this->history ? $this->enPassant() : explode(' ', $this->startFen)[3];
                if (!$this->pieceBySq($move['to']) && $enPassant !== $move['to']) {
                    return true;
                }
            } elseif (!$this->pieceBySq($move['to'])) {
                return true;
            }
        }
        $ambiguous = [];
        foreach ($pieces as $piece) {
            if (in_array($move['to'], $piece->moveSqs())) {
                $ambiguous[] = $move['to'];
            }
        }

        return count($ambiguous) > 1;
    }

    /**
     * Returns true if the move is legal.
     *
     * @param array $move
     * @param array $pieces
     * @return bool
     */
    protected function isLegal(array $move, array $pieces): bool
    {
        foreach ($pieces as $piece) {
            if ($piece->move['case'] === $this->move->case(Move::CASTLE_SHORT)) {
                return $piece->castle(RType::CASTLE_SHORT);
            } elseif ($piece->move['case'] === $this->move->case(Move::CASTLE_LONG)) {
                return $piece->castle(RType::CASTLE_LONG);
            } else {
                return $piece->move();
            }
        }

        return false;
    }

    /**
     * Removes an element from the history.
     *
     * @return \Chess\Variant\AbstractBoard
     */
    protected function popHistory(): AbstractBoard
    {
        array_pop($this->history);

        return $this;
    }

    /**
     * Converts a LAN move into an array of disambiguated PGN moves.
     *
     * @param string $color
     * @param string $lan
     * @return array
     */
    protected function lanToPgn(string $color, string $lan): array
    {
        $pgn = [];
        $sqs = $this->move->explodeSqs($lan);
        if (isset($sqs[0]) && isset($sqs[1])) {
            $a = $this->pieceBySq($sqs[0]);
            $b = $this->pieceBySq($sqs[1]);
            if ($a) {
                if ($a->id === Piece::K) {
                    if ($a->sqCastle(Castle::SHORT)) {
                        $pgn[] = Castle::SHORT;
                    } elseif ($a->sqCastle(Castle::LONG)) {
                        $pgn[] = Castle::LONG;
                    } elseif ($b) {
                        $pgn[] = "{$a->id}x{$sqs[1]}";
                    } else {
                        $pgn[] = "{$a->id}{$sqs[1]}";
                    }
                } elseif ($a->id === Piece::P) {
                    if ($b) {
                        $pgn[] = "{$a->file()}x{$sqs[1]}";
                    } elseif ($a->enPassantSq) {
                        $pgn[] = "{$a->file()}x{$a->enPassantSq}";
                    } else {
                        $pgn[] = $sqs[1];
                    }
                    $newId = mb_substr($lan, -1);
                    if (ctype_alpha($newId)) {
                        $pgn[0] .= '=' . mb_strtoupper($newId);
                    }
                } else {
                    if ($b) {
                        $pgn[] = "{$a->id}x{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->file()}x{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->rank()}x{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->sq}x{$sqs[1]}";
                    } else {
                        $pgn[] = "{$a->id}{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->file()}{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->rank()}{$sqs[1]}";
                        $pgn[] = "{$a->id}{$a->sq}{$sqs[1]}";
                    }
                }
            }
        }

        return $pgn;
    }

    /**
     * Updates the history after a LAN move has been played.
     *
     * @return bool
     */
    protected function afterPlayLan(): bool
    {
        if ($this->isMate()) {
            $this->history[count($this->history) - 1]['pgn'] .= '#';
        } elseif ($this->isCheck()) {
            $this->history[count($this->history) - 1]['pgn'] .= '+';
        }

        return true;
    }

    /**
     * Count squares.
     *
     * @return array
     */
    public function sqCount(): array
    {
        $used = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->pieces() as $piece) {
            $used[$piece->color][] = $piece->sq;
        }

        return [
            'free' => array_diff($this->square->all, [...$used[Color::W], ...$used[Color::B]]),
            'used' => $used,
        ];
    }

    /**
     * Refreshes the board.
     */
    public function refresh(): void
    {
        $this->turn = $this->color->opp($this->turn);

        $this->sqCount = $this->sqCount();

        $this->detachPieces()
            ->attachPieces()
            ->notifyPieces();

        $this->spaceEval = (new SpaceEval($this))->result;

        if ($this->history) {
            $this->history[count($this->history) - 1]['fen'] = $this->toFen();
        }
    }

    /**
     * Returns the movetext.
     *
     * @return string
     */
    public function movetext(): string
    {
        $movetext = '';
        foreach ($this->history as $key => $val) {
            if ($key === 0) {
                $movetext .= $val['color'] === Color::W
                    ? "1.{$val['pgn']}"
                    : '1' . Move::ELLIPSIS . "{$val['pgn']} ";
            } else {
                if ($this->history[0]['color'] === Color::W) {
                    $movetext .= $key % 2 === 0
                        ? ($key / 2 + 1) . ".{$val['pgn']}"
                        : " {$val['pgn']} ";
                } else {
                    $movetext .= $key % 2 === 0
                        ? " {$val['pgn']} "
                        : (ceil($key / 2) + 1) . ".{$val['pgn']}";
                }
            }
        }

        return trim($movetext);
    }

    /**
     * Returns a piece by color and id.
     *
     * @param string $color
     * @param string $id
     * @return \Chess\Variant\AbstractPiece|null
     */
    public function piece(string $color, string $id): ?AbstractPiece
    {
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->color === $color && $piece->id === $id) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Returns a piece by its position on the board.
     *
     * @param string $sq
     * @return \Chess\Variant\AbstractPiece|null
     */
    public function pieceBySq(string $sq): ?AbstractPiece
    {
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->sq === $sq) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Returns all pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function pieces(string $color = ''): array
    {
        $pieces = [];
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($color) {
                if ($piece->color === $color) {
                    $pieces[] = $piece;
                }
            } else {
                $pieces[] = $piece;
            }
            $this->next();
        }

        return $pieces;
    }

    /**
     * Makes a move in PGN format.
     *
     * @param string $color
     * @param string $pgn
     * @return bool
     */
    public function play(string $color, string $pgn): bool
    {
        $pieces = [];
        $move = $this->move->toArray($color, $pgn, $this->castlingRule, $this->color);
        foreach ($this->pickPiece($move) as $piece) {
            if ($piece->isMovable()) {
                if (!$piece->isLeftInCheck()) {
                    $pieces[] = $piece;
                }
            }
        }
        if (!$this->isAmbiguous($move, $pieces)) {
            return $this->isLegal($move, $pieces);
        }

        return false;
    }

    /**
     * Makes a move in LAN format.
     *
     * @param string $color
     * @param string $lan
     * @return bool
     */
    public function playLan(string $color, string $lan): bool
    {
        if ($color === $this->turn) {
            foreach ($this->lanToPgn($color, $lan) as $val) {
                if ($this->play($color, $val)) {
                    return $this->afterPlayLan();
                }
            }
        }

        return false;
    }

    /**
     * Undo the last move.
     *
     * @return \Chess\Variant\AbstractBoard
     */
    public function undo(): AbstractBoard
    {
        if (count($this->history) > 1) {
            $beforeLast = array_slice($this->history, -2)[0];
            $board = FenToBoardFactory::create($beforeLast['fen'], $this);
            $board->history = $this->popHistory()->history;
            $board->startFen = $this->startFen;
            return $board;
        }

        return FenToBoardFactory::create($this->startFen, $this);
    }

    /**
     * Returns true if the king is in check.
     *
     * @return bool
     */
    public function isCheck(): bool
    {
        return $this->piece($this->turn, Piece::K)->attacking() !== [];
    }

    /**
     * Returns true if the king is checkmated.
     *
     * @return bool
     */
    public function isMate(): bool
    {
        if ($king = $this->piece($this->turn, Piece::K)) {
            if ($attacking = $king->attacking()) {
                if (count($attacking) === 1) {
                    foreach ($attacking[0]->attacking() as $attackingAttacking) {
                        if (!$attackingAttacking->isPinned()) {
                            return false;
                        }
                    }
                    $moveSqs = [];
                    foreach ($this->pieces($this->turn) as $piece) {
                        if (!$piece->isPinned()) {
                            $moveSqs = [
                                ...$moveSqs,
                                ...$piece->moveSqs(),
                            ];
                        }
                    }
                    $lineOfAttack = $attacking[0]->lineOfAttack();
                    return $king->moveSqs() === [] &&
                        array_intersect($lineOfAttack, $moveSqs) === [];
                } elseif (count($attacking) > 1) {
                    return $king->moveSqs() === [];
                }
            }
        }

        return false;
    }

    /**
     * Returns true if the king is stalemate.
     *
     * @return bool
     */
    public function isStalemate(): bool
    {
        if (!$this->piece($this->turn, Piece::K)->attacking()) {
            $moveSqs = [];
            foreach ($this->pieces($this->turn) as $piece) {
                if (!$piece->isPinned()) {
                    $moveSqs = [
                        ...$moveSqs,
                        ...$piece->moveSqs(),
                    ];
                }
            }
            return $moveSqs === [];
        }

        return false;
    }

    /**
     * Returns true if the game results in a draw by fivefold repetition.
     *
     * @return bool
     */
    public function isFivefoldRepetition(): bool
    {
        $count = array_count_values(array_column($this->history, 'fen'));
        foreach ($count as $key => $val) {
            if ($val >= 5) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the game results in a draw by the fifty-move rule.
     *
     * @return bool
     */
    public function isFiftyMoveDraw(): bool
    {
        return count($this->history) >= 100;

        foreach (array_reverse($this->history) as $key => $value) {
            if ($key < 100) {
                if (str_contains($value->move->case, 'x')) {
                    return  false;
                } elseif (
                    $value->move->case === $this->move->case(Move::PAWN) ||
                    $value->move->case === $this->move->case(Move::PAWN_PROMOTES)
                ) {
                    return  false;
                }
            }
        }

        return true;
    }

    /**
     * Returns true if the game results in a draw because of a dead position.
     *
     * @return bool
     */
    public function isDeadPositionDraw(): bool
    {
        $count = count($this->pieces());
        if ($count === 2) {
            return true;
        } elseif ($count === 3) {
            foreach ($this->pieces() as $piece) {
                if ($piece->id === Piece::N) {
                    return true;
                } elseif ($piece->id === Piece::B) {
                    return true;
                }
            }
        } elseif ($count === 4) {
            $colors = '';
            foreach ($this->pieces() as $piece) {
                if ($piece->id === Piece::B) {
                    $colors .= $this->square->color($piece->sq);
                }
            }
            return $colors === Color::W . Color::W || $colors === Color::B . Color::B;
        }

        return false;
    }

    /**
     * Returns the legal moves of the given piece.
     *
     * @param string $sq
     * @return array
     */
    public function legal(string $sq): array
    {
        $legal = [];
        if ($piece = $this->pieceBySq($sq)) {
            foreach ($piece->moveSqs() as $moveSq) {
                $clone = $this->clone();
                if ($piece->id === Piece::K || $piece->id === Piece::P) {
                    if ($clone->playLan($this->turn, "$sq$moveSq")) {
                        $legal[] = $moveSq;
                    }
                } else {
                    if ($clone->play($this->turn, "{$piece->id}{$sq}{$moveSq}")) {
                        $legal[] = $moveSq;
                    } elseif ($clone->play($this->turn, "{$piece->id}{$sq}x{$moveSq}")) {
                        $legal[] = $moveSq;
                    }
                }
            }
        }

        return $legal;
    }

    /**
     * Returns the en passant square of the current position.
     *
     * @return string
     */
    public function enPassant(): string
    {
        if ($this->history) {
            $last = array_slice($this->history, -1)[0];
            if ($last['id'] === Piece::P) {
                $prevFile = substr($last['from'], 1);
                $nextFile = substr($last['to'], 1);
                if ($last['color'] === Color::W) {
                    if ($nextFile - $prevFile === 2) {
                        return $last['from'][0] . $prevFile + 1;
                    }
                } elseif ($prevFile - $nextFile === 2) {
                    return $last['from'][0] . $prevFile - 1;
                }
            }
        }

        return '-';
    }

    /**
     * Returns an array representing the current position.
     *
     * @return array
     */
    public function toArray(bool $flip = false): array
    {
        $array = [];
        for ($i = $this->square::SIZE['ranks'] - 1; $i >= 0; $i--) {
            $array[$i] = array_fill(0, $this->square::SIZE['files'], ' . ');
        }
        foreach ($this->pieces() as $piece) {
            list($file, $rank) = $this->square->toIndex($piece->sq);
            if ($flip) {
                $diff = $this->square::SIZE['files'] - $this->square::SIZE['ranks'];
                $file = $this->square::SIZE['files'] - 1 - $file - $diff;
                $rank = $this->square::SIZE['ranks'] - 1 - $rank + $diff;
            }
            $piece->color === Color::W
                ? $array[$file][$rank] = ' ' . $piece->id . ' '
                : $array[$file][$rank] = ' ' . strtolower($piece->id) . ' ';
        }

        return $array;
    }

    /**
     * Returns a string representing the current position.
     *
     * @return string
     */
    public function toString(bool $flip = false): string
    {
        $ascii = '';
        $array = $this->toArray($flip);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }

    /**
     * Returns a FEN string representing the current position.
     *
     * @return string
     */
    public function toFen(): string
    {
        $filtered = '';
        foreach ($this->toArray() as $rank) {
            $filtered .= implode($rank) . '/';
        }
        $filtered = str_replace(' ', '', substr($filtered, 0, -1));
        for ($i = $this->square::SIZE['files']; $i >= 1; $i--) {
            $filtered = str_replace(str_repeat('.', $i), $i, $filtered);
        }

        return "{$filtered} {$this->turn} {$this->castlingAbility} {$this->enPassant()}";
    }

    /**
     * Returns the difference of two arrays of pieces.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function diffPieces(array $array1, array $array2): array
    {
        return array_udiff($array2, $array1, function ($b, $a) {
            return $a->sq <=> $b->sq;
        });
    }

    /**
     * Returns true if the game results in a draw.
     *
     * @return bool
     */
    public function doesDraw(): bool
    {
        return false;
    }

    /**
     * Returns true if the game results in a win for one side.
     *
     * @return bool
     */
    public function doesWin(): bool
    {
        return false;
    }

    /**
     * Returns a clone of the board.
     *
     * @return \Chess\Variant\AbstractBoard
     */
    public function clone(): AbstractBoard
    {
        return unserialize(serialize($this));
    }
}
