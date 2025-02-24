<?php

namespace Chess\Variant;

use Chess\Eval\SpaceEval;
use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Classical\K;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
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
    public string $castlingAbility = CastlingRule::NEITHER;

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
     * Halfmove clock.
     *
     * @var int
     */
    public int $halfMoveClock = 0;

    /**
     * Picks a piece from the board.
     *
     * @param array $move
     * @return array
     */
    protected function pick(array $move): array
    {
        $pieces = [];
        foreach ($this->pieces($move['color']) as $piece) {
            if ($piece->id === $move['id'] && str_contains($piece->sq, $move['from'])) {
                $piece->move = $move;
                $pieces[] = $piece;
            }
        }

        return $pieces;
    }

    /**
     * Returns a disambiguated piece from an array of pieces.
     *
     * @param array $move
     * @param array $pieces
     * @return null|\Chess\Variant\AbstractPiece
     */
    protected function disambiguate(array $move, array $pieces): ?AbstractPiece
    {
        $ambiguous = [];
        foreach ($pieces as $piece) {
            if (in_array($move['to'], $piece->moveSqs())) {
                $ambiguous[] = $move['to'];
            }
        }

        return count($ambiguous) === 1 ? $pieces[0] : null;
    }

    /**
     * Returns true if the move is legal.
     *
     * @param array $move
     * @param \Chess\Variant\AbstractPiece $piece
     * @return bool
     */
    protected function isLegal(array $move, AbstractPiece $piece): bool
    {
        if ($piece->move['case'] === $this->move->case(Move::CASTLE_SHORT)) {
            return $piece->castle(RType::CASTLE_SHORT);
        } elseif ($piece->move['case'] === $this->move->case(Move::CASTLE_LONG)) {
            return $piece->castle(RType::CASTLE_LONG);
        }

        return $piece->move();
    }

    /**
     * Converts a LAN move into a pseudo-move in PGN format. 
     * 
     * This is an intermediate step required to make a move in LAN format.
     * The pseudo-PGN fromat is characterized by a double disambiguation — the
     * file and the rank of departure — used to identify a piece. The notation
     * for both check and checkmate is omitted.
     *
     * @param string $color
     * @param string $lan
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    protected function lanToPseudoPgn(string $color, string $lan): string
    {
        $sqs = $this->square->explode($lan);
        if (!isset($sqs[0]) && !isset($sqs[1])) {
            throw new UnknownNotationException();
        }
        if ($color === $this->turn) {
            if ($a = $this->pieceBySq($sqs[0])) {
                $x = $this->pieceBySq($sqs[1]) ? 'x' : '';
                if ($a->id === Piece::K) {
                    if ($a->sqCastle(Castle::SHORT) === $sqs[1]) {
                        return Castle::SHORT;
                    } elseif ($a->sqCastle(Castle::LONG) === $sqs[1]) {
                        return Castle::LONG;
                    } else {
                        return "{$a->id}{$x}{$sqs[1]}";
                    }
                } elseif ($a->id === Piece::P) {
                    if ($this->square->promoRank($color) === (int) substr($sqs[1], 1)) {
                        $newId = mb_strtoupper(mb_substr($lan, -1));
                        $promo = ctype_alpha($newId) ? '=' . $newId : '=' . Piece::Q;
                    } else {
                        $promo = '';
                    }
                    return $x || $a->xEnPassantSq === $sqs[1]
                        ? "{$a->file()}x{$sqs[1]}{$promo}"
                        : "{$sqs[1]}{$promo}";
                } else {
                    return "{$a->id}{$a->sq}{$x}{$sqs[1]}";
                }
            }
        }
        
        return '';
    }

    /**
     * Fixes the pseudo-PGN move in the history array after a LAN move is made.
     *
     * @return bool
     */
    protected function onPlayLan(): bool
    {
        // undo the double disambiguation
        $last = $this->history[count($this->history) - 1];
        if (preg_match('/^' . Move::PIECE . '$/', $last['pgn']) ||
            preg_match('/^' . Move::PIECE_CAPTURES . '$/', $last['pgn'])
        ) {
            $sqs = $this->square->explode($last['pgn']);
            if (isset($sqs[0]) && isset($sqs[1])) {
                if ($piece = $this->pieceBySq($sqs[1])) {
                    $disambiguation = $sqs[0];
                    $x = str_contains($last['pgn'], 'x') ? 'x' : '';
                    foreach ($piece->defending() as $defending) {
                        if ($defending->id === $piece->id) {
                            $file = $sqs[0][0];
                            $rank = (int) substr($sqs[0], 1);
                            $disambiguation = $file  === $defending->file()
                                ? str_replace($file , '', $disambiguation)
                                : str_replace($rank, '', $disambiguation);
                        }
                    }
                    $this->history[count($this->history) - 1]['pgn'] = $disambiguation === $sqs[0]
                        ? $piece->id . $x . $sqs[1]
                        : $piece->id . $disambiguation . $x . $sqs[1];
                }
            }
        }
        // add the notation for check and checkmate to the move
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
        $this->turn = $this->turn === Color::W ? Color::B : Color::W;
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
     * Returns the en passant capture pawns.
     *
     * @return array
     */
    public function xEnPassantPawns(): array
    {
        $pieces = [];
        $color = $this->turn === Color::W ? Color::B : Color::W;
        foreach ($this->pieces($color) as $piece) {
            if ($piece->id === Piece::P && $piece->xEnPassantSq) {
                $pieces[] = $piece;
            }
        }

        return $pieces;
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
        if ($color === $this->turn) {
            $pieces = [];
            $move = $this->move->toArray($color, $pgn, $this->square, $this->castlingRule);
            foreach ($this->pick($move) as $piece) {
                if ($piece->isMovable() && !$piece->isKingLeftInCheck()) {
                    $pieces[] = $piece;
                }
            }
            if ($piece = $this->disambiguate($move, $pieces)) {
                return $this->isLegal($move, $piece);
            }
        }

        return false;
    }

    /**
     * Makes a move in LAN format delegating the call to the PGN parser.
     *
     * @param string $color
     * @param string $lan
     * @return bool
     */
    public function playLan(string $color, string $lan): bool
    {
        if ($color === $this->turn) {
            if ($pgn = $this->lanToPseudoPgn($color, $lan)) {
                if ($this->play($color, $pgn)) {
                    return $this->onPlayLan();
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
        $startFen = count($this->history) > 1
            ? array_slice($this->history, -2)[0]['fen']
            : $this->startFen;
        $namespace = (new \ReflectionClass(get_class($this)))->getNamespaceName();
        $board = "$namespace\FenToBoardFactory"::create($startFen, $this);
        $this->castlingAbility = $board->castlingAbility;
        $this->halfMoveClock = explode(' ', $startFen)[4];
        array_pop($this->history);
        $this->rewind();
        while ($this->valid()) {
            $this->detach($this->current());
            $this->next();
        }
        foreach ($board->pieces() as $piece) {
            $this->attach($piece);
        }
        $this->refresh();        

        return $this;
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
                    $line = $this->square->line($attacking[0]->sq, $this->piece($this->turn, Piece::K)->sq);
                    return $king->moveSqs() === [] && array_intersect($line, $moveSqs) === [];
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
        return $this->halfMoveClock >= 100;
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
                if ($piece->id === Piece::N xor $piece->id === Piece::B) {
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
            return $colors === Color::W . Color::W xor $colors === Color::B . Color::B;
        }

        return false;
    }

    /**
     * Returns true if the given line of squares is empty of pieces.
     *
     * @param array $line
     * @return bool
     */
    public function isEmptyLine(array $line): bool
    {
        return !array_diff($line, $this->sqCount['free']);
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
                if ($this->clone()->playLan($this->turn, "$sq$moveSq")) {
                    $legal[] = $moveSq;
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
                if (abs($nextFile - $prevFile) === 2) {
                    return $last['color'] === Color::W
                        ? $last['from'][0] . $prevFile + 1
                        : $last['from'][0] . $prevFile - 1;
                }
            }
        }

        return CastlingRule::NEITHER;
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
            $array[$i] = array_fill(0, $this->square::SIZE['files'], '.');
        }
        foreach ($this->pieces() as $piece) {
            list($file, $rank) = $this->square->toIndices($piece->sq);
            if ($flip) {
                $diff = $this->square::SIZE['files'] - $this->square::SIZE['ranks'];
                $file = $this->square::SIZE['files'] - 1 - $file - $diff;
                $rank = $this->square::SIZE['ranks'] - 1 - $rank + $diff;
            }
            $piece->color === Color::W
                ? $array[$file][$rank] = $piece->id
                : $array[$file][$rank] = strtolower($piece->id);
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
                $ascii .= " {$array[$i][$j]} ";
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

        return "{$filtered} {$this->turn} {$this->castlingAbility} {$this->enPassant()} {$this->halfMoveClock}";
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
