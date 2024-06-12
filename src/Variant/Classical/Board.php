<?php

namespace Chess\Variant\Classical;

use Chess\FenToBoardFactory;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqCount;
use Chess\Exception\BoardException;
use Chess\Piece\AbstractPiece;
use Chess\Piece\AsciiArray;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

/**
 * Board
 *
 * Chess board representation to play classical chess.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Board extends AbstractPgnParser
{
    use BoardObserverPieceTrait;

    const VARIANT = 'classical';

    /**
     * Constructor.
     *
     * @param array $pieces
     * @param string $castlingAbility
     */
    public function __construct(
        array $pieces = null,
        string $castlingAbility = '-'
    ) {
        $this->color = new Color();
        $this->castlingRule = new CastlingRule();
        $this->square = new Square();
        $this->move = new Move();
        $this->castlingAbility = CastlingRule::START;
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1', $this->square));
            $this->attach(new B(Color::W, 'c1', $this->square));
            $this->attach(new Q(Color::W, 'd1', $this->square));
            $this->attach(new K(Color::W, 'e1', $this->square));
            $this->attach(new B(Color::W, 'f1', $this->square));
            $this->attach(new N(Color::W, 'g1', $this->square));
            $this->attach(new R(Color::W, 'h1', $this->square, RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2', $this->square));
            $this->attach(new P(Color::W, 'b2', $this->square));
            $this->attach(new P(Color::W, 'c2', $this->square));
            $this->attach(new P(Color::W, 'd2', $this->square));
            $this->attach(new P(Color::W, 'e2', $this->square));
            $this->attach(new P(Color::W, 'f2', $this->square));
            $this->attach(new P(Color::W, 'g2', $this->square));
            $this->attach(new P(Color::W, 'h2', $this->square));
            $this->attach(new R(Color::B, 'a8', $this->square, RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8', $this->square));
            $this->attach(new B(Color::B, 'c8', $this->square));
            $this->attach(new Q(Color::B, 'd8', $this->square));
            $this->attach(new K(Color::B, 'e8', $this->square));
            $this->attach(new B(Color::B, 'f8', $this->square));
            $this->attach(new N(Color::B, 'g8', $this->square));
            $this->attach(new R(Color::B, 'h8', $this->square, RType::CASTLE_SHORT));
            $this->attach(new P(Color::B, 'a7', $this->square));
            $this->attach(new P(Color::B, 'b7', $this->square));
            $this->attach(new P(Color::B, 'c7', $this->square));
            $this->attach(new P(Color::B, 'd7', $this->square));
            $this->attach(new P(Color::B, 'e7', $this->square));
            $this->attach(new P(Color::B, 'f7', $this->square));
            $this->attach(new P(Color::B, 'g7', $this->square));
            $this->attach(new P(Color::B, 'h7', $this->square));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
            $this->castlingAbility = $castlingAbility;
        }

        $this->refresh();

        $this->startFen = $this->toFen();
    }

    /**
     * Refreshes the state of the board.
     */
    public function refresh(): void
    {
        $this->turn = $this->color->opp($this->turn);

        $this->sqCount = (new SqCount($this))->count();

        $this->detachPieces()
            ->attachPieces()
            ->notifyPieces();

        $this->spaceEval = (object) (new SpaceEval($this))->getResult();

        $this->notifyPieces();

        if ($this->history) {
            $this->history[count($this->history) - 1]['fen'] = $this->toFen();
        }
    }

    /**
     * Returns the movetext.
     *
     * @return string
     */
    public function getMovetext(): string
    {
        $movetext = '';
        foreach ($this->history as $key => $val) {
            if ($key === 0) {
                $movetext .= $val['move']['color'] === Color::W
                    ? "1.{$val['move']['pgn']}"
                    : '1' . Move::ELLIPSIS . "{$val['move']['pgn']} ";
            } else {
                if ($this->history[0]['move']['color'] === Color::W) {
                    $movetext .= $key % 2 === 0
                        ? ($key / 2 + 1) . ".{$val['move']['pgn']}"
                        : " {$val['move']['pgn']} ";
                } else {
                    $movetext .= $key % 2 === 0
                        ? " {$val['move']['pgn']} "
                        : (ceil($key / 2) + 1) . ".{$val['move']['pgn']}";
                }
            }
        }

        return trim($movetext);
    }

    /**
     * Returns the first piece on the board matching the search criteria.
     *
     * @param string $color
     * @param string $id
     * @return AbstractPiece|null \Chess\Piece\AbstractPiece|null
     */
    public function getPiece(string $color, string $id): ?AbstractPiece
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
     * Returns an array of pieces.
     *
     * @param string $color
     * @return array
     */
    public function getPieces(string $color = ''): array
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
     * Returns a piece by its position on the board.
     *
     * @param string $sq
     * @return AbstractPiece|null \Chess\Piece\AbstractPiece|null
     */
    public function getPieceBySq(string $sq): ?AbstractPiece
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
     * Makes a move in PGN format.
     *
     * @param string $color
     * @param string $pgn
     * @return bool
     */
    public function play(string $color, string $pgn): bool
    {
        $move = $this->move->toArray($color, $pgn, $this->castlingRule, $this->color);
        if ($this->isValidMove($move)) {
            return $this->isLegalMove($move);
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
        $sqs = $this->move->explodeSqs($lan);
        if (isset($sqs[0]) && isset($sqs[1])) {
            if ($color === $this->turn && $piece = $this->getPieceBySq($sqs[0])) {
                if ($piece->id === Piece::K) {
                    if (
                        $this->castlingRule->getRule()[$color][Piece::K][Castle::SHORT]['sq']['next'] === $sqs[1] &&
                        $piece->sqCastleShort() &&
                        $this->play($color, Castle::SHORT)
                    ) {
                        return $this->afterPlayLan();
                    } elseif (
                        $this->castlingRule->getRule()[$color][Piece::K][Castle::LONG]['sq']['next'] === $sqs[1] &&
                        $piece->sqCastleLong() &&
                        $this->play($color, Castle::LONG)
                    ) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, Piece::K . 'x' . $sqs[1])) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, Piece::K . $sqs[1])) {
                        return $this->afterPlayLan();
                    }
                } elseif ($piece->id === Piece::P) {
                    strlen($lan) === 5
                        ? $promotion = '=' . mb_strtoupper(substr($lan, -1))
                        : $promotion = '';
                    if ($this->play($color, $piece->file() . "x$sqs[1]" . $promotion)) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, $sqs[1] . $promotion)) {
                        return $this->afterPlayLan();
                    }
                } else {
                    if ($this->play($color, "{$piece->id}x$sqs[1]")) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, "{$piece->id}{$piece->file()}x$sqs[1]")) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, "{$piece->id}{$piece->rank()}x$sqs[1]")) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, $piece->id . $sqs[1])) {
                        return $this->afterPlayLan();
                    }  elseif ($this->play($color, $piece->id . $piece->file() . $sqs[1])) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, $piece->id . $piece->rank() . $sqs[1])) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, "{$piece->id}{$piece->sq}x$sqs[1]")) {
                        return $this->afterPlayLan();
                    } elseif ($this->play($color, $piece->id . $piece->sq . $sqs[1])) {
                        return $this->afterPlayLan();
                    }
                }
            }
        }

        return false;
    }

    /**
     * Adds a PGN symbol to the last history element.
     *
     * @return bool
     */
    protected function afterPlayLan(): bool
    {
        if ($this->isMate()) {
            $this->history[count($this->history) - 1]['move']['pgn'] .= '#';
        } elseif ($this->isCheck()) {
            $this->history[count($this->history) - 1]['move']['pgn'] .= '+';
        }

        return true;
    }

    /**
     * Undoes the last move.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function undo(): Board
    {
        $board = FenToBoardFactory::create($this->startFen, $this);
        foreach ($this->popHistory()->history as $key => $val) {
            $board->play($val['move']['color'], $val['move']['pgn']);
        }

        return $board;
    }

    /**
     * Checks out whether the current player is in check.
     *
     * @return bool
     */
    public function isCheck(): bool
    {
        $king = $this->getPiece($this->turn, Piece::K);

        if ($king) {
            return !empty($king->attackingPieces());
        }

        throw new BoardException();
    }

    /**
     * Checks out whether the current player is checkmated.
     *
     * @return bool
     */
    public function isMate(): bool
    {
        return $this->isTrapped() && $this->isCheck();
    }

    /**
     * Checks out whether the current player is stalemated.
     *
     * @return bool
     */
    public function isStalemate(): bool
    {
        return $this->isTrapped() && !$this->isCheck();
    }

    /**
     * Checks out whether the same position occurs five times.
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
     * Checks out if no capture has been made and no pawn has been moved in the
     * last fifty moves.
     *
     * @return bool
     */
    public function isFiftyMoveDraw(): bool
    {
        return count($this->history) >= 100;

        foreach (array_reverse($this->history) as $key => $value) {
            if ($key < 100) {
                if ($value->move->isCapture) {
                    return  false;
                } elseif (
                    $value->move->type === $this->move->case(Move::PAWN) ||
                    $value->move->type === $this->move->case(Move::PAWN_PROMOTES)
                ) {
                    return  false;
                }
            }
        }

        return true;
    }

    /**
     * Returns true if no sequence of legal moves can lead to checkmate.
     *
     * @return bool
     */
    public function isDeadPositionDraw(): bool
    {
        $count = count($this->getPieces());
        if ($count === 2) {
            return true;
        } elseif ($count === 3) {
            foreach ($this->getPieces() as $piece) {
                if ($piece->id === Piece::N) {
                    return true;
                } elseif ($piece->id === Piece::B) {
                    return true;
                }
            }
        } elseif ($count === 4) {
            $colors = '';
            foreach ($this->getPieces() as $piece) {
                if ($piece->id === Piece::B) {
                    $colors .= $this->square->color($piece->sq);
                }
            }
            return $colors === Color::W . Color::W || $colors === Color::B . Color::B;
        }

        return false;
    }

    /**
     * Returns the legal squares of a piece.
     *
     * @param string $sq
     * @return array
     */
    public function legal(string $sq): array
    {
        return array_values($this->getPieceBySq($sq)->sqs());
    }

    /**
     * Returns the en passant square.
     *
     * @return string
     */
    public function enPassant(): string
    {
        if ($this->history) {
            $last = array_slice($this->history, -1)[0];
            if ($last['move']['id'] === Piece::P) {
                $prevFile = intval(substr($last['sq'], 1));
                $nextFile = intval(substr($last['move']['sq']['next'], 1));
                if ($last['move']['color'] === Color::W) {
                    if ($nextFile - $prevFile === 2) {
                        $rank = $prevFile + 1;
                        return $last['move']['sq']['current'] . $rank;
                    }
                } elseif ($prevFile - $nextFile === 2) {
                    $rank = $prevFile - 1;
                    return $last['move']['sq']['current'] . $rank;
                }
            }
        }

        return '-';
    }

    /**
     * Returns an ASCII array representing this chessboard object.
     *
     * @param bool $flip
     * @return array
     */
    public function toAsciiArray(bool $flip = false): array
    {
        $array = [];
        for ($i = $this->square::SIZE['ranks'] - 1; $i >= 0; $i--) {
            $array[$i] = array_fill(0, $this->square::SIZE['files'], ' . ');
        }

        foreach ($this->getPieces() as $piece) {
            list($file, $rank) = AsciiArray::fromAlgebraicToIndex($piece->sq);
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
     * Returns an ASCII string representing this Chess\Board object.
     *
     * @param bool $flip
     * @return string
     */
    public function toAsciiString(bool $flip = false): string
    {
        $ascii = '';
        $array = $this->toAsciiArray($flip);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }

    /**
     * Returns a FEN representing this chessboard object.
     *
     * @return string
     */
    public function toFen(): string
    {
        $string = '';
        $array = $this->toAsciiArray();
        for ($i = $this->square::SIZE['ranks'] - 1; $i >= 0; $i--) {
            $string .= str_replace(' ', '', implode('', $array[$i]));
            if ($i != 0) {
                $string .= '/';
            }
        }

        $filtered = '';
        $strSplit = str_split($string);
        $n = 1;
        for ($i = 0; $i < count($strSplit); $i++) {
            if ($strSplit[$i] === '.') {
                if (isset($strSplit[$i + 1]) && $strSplit[$i + 1] === '.') {
                    $n++;
                } else {
                    $filtered .= $n;
                    $n = 1;
                }
            } else {
                $filtered .= $strSplit[$i];
                $n = 1;
            }
        }

        return "{$filtered} {$this->turn} {$this->castlingAbility} {$this->enPassant()}";
    }

    /**
     * Returns the difference between two arrays of pieces.
     *
     * @return array
     */
    public function diffPieces(array $array1, array $array2): array
    {
        return array_udiff($array2, $array1, function ($b, $a) {
            return $a->sq <=> $b->sq;
        });
    }

    /**
     * Clones the board.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function clone(): Board
    {
        $board = FenToBoardFactory::create($this->toFen(), $this);
        $board->captures = $this->captures;
        $board->history = $this->history;

        return $board;
    }
}
