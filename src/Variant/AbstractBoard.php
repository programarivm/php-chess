<?php

namespace Chess\Variant;

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
use Chess\Piece\VariantType;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

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
     * Captured pieces.
     *
     * @var array
     */
    public array $captures = [
        Color::W => [],
        Color::B => [],
    ];

    /**
     * History.
     *
     * @var array
     */
    public array $history = [];

    /**
     * Color.
     *
     * @var \Chess\Variant\Classical\PGN\AN\Color
     */
    public Color $color;

    /**
     * Castling rule.
     *
     * @var \Chess\Variant\Classical\Rule\CastlingRule
     */
    public CastlingRule $castlingRule;

    /**
     * Castling ability.
     *
     * @var string
     */
    public string $castlingAbility = '';

    /**
     * Piece variant.
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
     * Square.
     *
     * @var \Chess\Variant\Classical\PGN\AN\Square
     */
    public Square $square;

    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    public Move $move;

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
                if (strstr($piece->sq, $move['sq']['current'])) {
                    $piece->move = $move;
                    $pieces[] = $piece;
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns true if the move is syntactically valid.
     *
     * @param array $move
     * @return bool
     */
    protected function isValidMove(array $move): bool
    {
        if ($this->isAmbiguousCapture($move)) {
            return false;
        } elseif ($this->isAmbiguousMove($move)) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the capture move is ambiguous.
     *
     * @param array $move
     * @return bool
     */
    protected function isAmbiguousCapture(array $move): bool
    {
        if ($move['isCapture']) {
            if ($move['id'] === Piece::P) {
                $enPassant = $this->history
                    ? $this->enPassant()
                    : explode(' ', $this->startFen)[3];
                if (!$this->pieceBySq($move['sq']['next']) && $enPassant !== $move['sq']['next']) {
                    return true;
                }
            } else {
                if (!$this->pieceBySq($move['sq']['next'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if the move is ambiguous.
     *
     * @param array $move
     * @return bool
     */
    protected function isAmbiguousMove(array $move): bool
    {
        $ambiguous = [];
        foreach ($this->pickPiece($move) as $piece) {
            if (in_array($move['sq']['next'], $piece->legalSqs())) {
                if (!$this->isPinned($piece)) {
                    $ambiguous[] = $move['sq']['next'];
                }
            }
        }

        return count($ambiguous) > 1;
    }

    /**
     * Returns true if the move is legal.
     *
     * @param array $move
     * @return bool
     */
    protected function isLegalMove(array $move): bool
    {
        foreach ($pieces = $this->pickPiece($move) as $piece) {
            if ($piece->isMovable()) {
                if (!$this->isPinned($piece)) {
                    if ($piece->move['type'] === $this->move->case(Move::CASTLE_SHORT)) {
                        return $this->castle($piece, RType::CASTLE_SHORT);
                    } elseif ($piece->move['type'] === $this->move->case(Move::CASTLE_LONG)) {
                        return $this->castle($piece, RType::CASTLE_LONG);
                    } else {
                        return $this->move($piece);
                    }
                }
            }
        }

        return false;
    }

    /**
     * Makes a move.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool
     */
    protected function move(AbstractPiece $piece): bool
    {
        if ($piece->move['isCapture']) {
            $this->capture($piece);
        }
        if ($toDetach = $this->pieceBySq($piece->sq)) {
            $this->detach($toDetach);
        }
        $class = VariantType::getClass($this->variant, $piece->id);
        $this->attach(new $class(
            $piece->color,
            $piece->move['sq']['next'],
            $this->square,
            $piece->id === Piece::R ? $piece->type : null
        ));
        if ($piece->id === Piece::P) {
            if ($piece->isPromoted()) {
                $this->promote($piece);
            }
        }
        $this->updateCastle($piece)->pushHistory($piece)->refresh();

        return true;
    }

    /**
     * Castles the king.
     *
     * @param \Chess\Piece\K $king
     * @param string $rookType
     * @return bool
     */
    protected function castle(K $king, string $rookType): bool
    {
        if ($rook = $king->getCastleRook($rookType)) {
            $this->detach($this->pieceBySq($king->sq));
            $this->attach(
                new K(
                    $king->color,
                    $this->castlingRule->rule[$king->color][Piece::K][rtrim($king->move['pgn'], '+')]['sq']['next'],
                    $this->square
                )
             );
            $this->detach($rook);
            $this->attach(
                new R(
                    $rook->color,
                    $this->castlingRule->rule[$king->color][Piece::R][rtrim($king->move['pgn'], '+')]['sq']['next'],
                    $this->square,
                    $rook->type
                )
            );
            $this->castlingAbility = $this->castlingRule->castle($this->castlingAbility, $this->turn);
            $this->pushHistory($king)->refresh();
            return true;
        }

        return false;
    }

    /**
     * Updates the castle property.
     *
     * @param \Chess\Piece\AbstractPiece $piece The moved piece
     * @return \Chess\Variant\Classical\AbstractBoard
     */
    protected function updateCastle(AbstractPiece $piece): AbstractBoard
    {
        if ($this->castlingRule->can($this->castlingAbility, $this->turn)) {
            if ($piece->id === Piece::K) {
                $this->castlingAbility = $this->castlingRule->update(
                    $this->castlingAbility,
                    $this->turn,
                    [Piece::K, Piece::Q]
                );
            } elseif ($piece->id === Piece::R) {
                if ($piece->type === RType::CASTLE_SHORT) {
                    $this->castlingAbility = $this->castlingRule->update(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::K]
                    );
                } elseif ($piece->type === RType::CASTLE_LONG) {
                    $this->castlingAbility = $this->castlingRule->update(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::Q]
                    );
                }
            }
        }
        $oppColor = $this->color->opp($this->turn);
        if ($this->castlingRule->can($this->castlingAbility, $oppColor)) {
            if ($piece->move['isCapture']) {
                if ($piece->move['sq']['next'] ===
                    $this->castlingRule->rule[$oppColor][Piece::R][Castle::SHORT]['sq']['current']
                ) {
                    $this->castlingAbility = $this->castlingRule->update(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::K]
                    );
                } elseif (
                    $piece->move['sq']['next'] ===
                    $this->castlingRule->rule[$oppColor][Piece::R][Castle::LONG]['sq']['current']
                ) {
                    $this->castlingAbility = $this->castlingRule->update(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::Q]
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Captures a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\AbstractBoard
     */
    protected function capture(AbstractPiece $piece): AbstractBoard
    {
        if (
            $piece->id === Piece::P &&
            $piece->enPassantSq &&
            !$this->pieceBySq($piece->move['sq']['next'])
        ) {
            if ($captured = $piece->enPassantPawn()) {
                $capturedData = [
                    'id' => $captured->id,
                    'sq' => $captured->sq,
                ];
            }
        } elseif ($captured = $this->pieceBySq($piece->move['sq']['next'])) {
            $capturedData = [
                'id' => $captured->id,
                'sq' => $captured->sq,
            ];
        }
        if ($captured) {
            $capturingData = [
                'id' => $piece->id,
                'sq' => $piece->sq,
            ];
            $piece->id !== Piece::R ?: $capturingData['type'] = $piece->type;
            $captured->id !== Piece::R ?: $capturedData['type'] = $captured->type;
            $this->captures[$piece->color][] = [
                'capturing' => $capturingData,
                'captured' => $capturedData,
            ];
            $this->detach($captured);
        }

        return $this;
    }

    /**
     * Promotes a pawn.
     *
     * @param \Chess\Piece\P $pawn
     * @return \Chess\Variant\Classical\AbstractBoard
     */
    protected function promote(P $pawn): AbstractBoard
    {
        $this->detach($this->pieceBySq($pawn->move['sq']['next']));
        if ($pawn->move['newId'] === Piece::N) {
            $this->attach(new N(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        } elseif ($pawn->move['newId'] === Piece::B) {
            $this->attach(new B(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        } elseif ($pawn->move['newId'] === Piece::R) {
            $this->attach(new R(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square,
                RType::PROMOTED
            ));
        } else {
            $this->attach(new Q(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        }

        return $this;
    }

    /**
     * Returns true if the current player is trapped.
     *
     * @return bool
     */
    protected function isTrapped(): bool
    {
        $escape = 0;
        foreach ($this->pieces($this->turn) as $piece) {
            foreach ($piece->legalSqs() as $sq) {
                if ($piece->id === Piece::K) {
                    if ($sq === $piece->sqCastleShort()) {
                        $move = $this->move->toArray($this->turn, Castle::SHORT, $this->castlingRule, $this->color);
                    } elseif ($sq === $piece->sqCastleLong()) {
                        $move = $this->move->toArray($this->turn, CASTLE::LONG, $this->castlingRule, $this->color);
                    } elseif (in_array($sq, $this->sqCount['used'][$piece->oppColor()])) {
                        $move = $this->move->toArray($this->turn, Piece::K."x$sq", $this->castlingRule, $this->color);
                    } elseif (!in_array($sq, $this->spaceEval[$piece->oppColor()])) {
                        $move = $this->move->toArray($this->turn, Piece::K.$sq, $this->castlingRule, $this->color);
                    }
                } elseif ($piece->id === Piece::P) {
                    if (in_array($sq, $this->sqCount['used'][$piece->oppColor()])) {
                        $move = $this->move->toArray($this->turn, $piece->file()."x$sq", $this->castlingRule, $this->color);
                    } else {
                        $move = $this->move->toArray($this->turn, $sq, $this->castlingRule, $this->color);
                    }
                } else {
                    if (in_array($sq, $this->sqCount['used'][$piece->oppColor()])) {
                        $move = $this->move->toArray($this->turn, $piece->id."x$sq", $this->castlingRule, $this->color);
                    } else {
                        $move = $this->move->toArray($this->turn, $piece->id.$sq, $this->castlingRule, $this->color);
                    }
                }
                $piece->move = $move;
                $escape += (int) !$this->isPinned($piece);
            }
        }

        return $escape === 0;
    }

    /**
     * Returns true if the piece is pinned.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool
     */
    protected function isPinned(AbstractPiece $piece): bool
    {
        $clone = $this->clone();
        if (
            $piece->move['type'] === $clone->move->case(Move::CASTLE_SHORT) &&
            $clone->castle($piece, RType::CASTLE_SHORT)
        ) {
            $king = $clone->piece($piece->color, Piece::K);
        } elseif (
            $piece->move['type'] === $clone->move->case(Move::CASTLE_LONG) &&
            $clone->castle($piece, RType::CASTLE_LONG)
        ) {
            $king = $clone->piece($piece->color, Piece::K);
        } else {
            $clone->move($piece);
            $king = $clone->piece($piece->color, Piece::K);
        }

        if ($king) {
            return !empty($king->attacking());
        }

        throw new BoardException();
    }

    /**
     * Adds a new element to the history.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\AbstractBoard
     */
    protected function pushHistory(AbstractPiece $piece): AbstractBoard
    {
        $this->history[] = [
            'castlingAbility' => $this->castlingAbility,
            'sq' => $piece->sq,
            'move' => $piece->move,
        ];

        return $this;
    }

    /**
     * Removes an element from the history.
     *
     * @return \Chess\Variant\Classical\AbstractBoard
     */
    protected function popHistory(): AbstractBoard
    {
        array_pop($this->history);

        return $this;
    }

    public function refresh(): void
    {
        $this->turn = $this->color->opp($this->turn);

        $this->sqCount = (new SqCount($this))->count();

        $this->detachPieces()
            ->attachPieces()
            ->notifyPieces();

        $this->spaceEval = (new SpaceEval($this))->getResult();

        $this->notifyPieces();

        if ($this->history) {
            $this->history[count($this->history) - 1]['fen'] = $this->toFen();
        }
    }

    public function movetext(): string
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

    public function play(string $color, string $pgn): bool
    {
        $move = $this->move->toArray($color, $pgn, $this->castlingRule, $this->color);
        if ($this->isValidMove($move)) {
            return $this->isLegalMove($move);
        }

        return false;
    }

    public function playLan(string $color, string $lan): bool
    {
        $sqs = $this->move->explodeSqs($lan);
        if (isset($sqs[0]) && isset($sqs[1])) {
            if ($color === $this->turn && $piece = $this->pieceBySq($sqs[0])) {
                if ($piece->id === Piece::K) {
                    if (
                        $this->castlingRule->rule[$color][Piece::K][Castle::SHORT]['sq']['next'] === $sqs[1] &&
                        $piece->sqCastleShort() &&
                        $this->play($color, Castle::SHORT)
                    ) {
                        return $this->afterPlayLan();
                    } elseif (
                        $this->castlingRule->rule[$color][Piece::K][Castle::LONG]['sq']['next'] === $sqs[1] &&
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

    protected function afterPlayLan(): bool
    {
        if ($this->isMate()) {
            $this->history[count($this->history) - 1]['move']['pgn'] .= '#';
        } elseif ($this->isCheck()) {
            $this->history[count($this->history) - 1]['move']['pgn'] .= '+';
        }

        return true;
    }

    public function undo(): AbstractBoard
    {
        $board = FenToBoardFactory::create($this->startFen, $this);
        foreach ($this->popHistory()->history as $key => $val) {
            $board->play($val['move']['color'], $val['move']['pgn']);
        }

        return $board;
    }

    public function isCheck(): bool
    {
        $king = $this->piece($this->turn, Piece::K);

        if ($king) {
            return !empty($king->attacking());
        }

        throw new BoardException();
    }

    public function isMate(): bool
    {
        return $this->isTrapped() && $this->isCheck();
    }

    public function isStalemate(): bool
    {
        return $this->isTrapped() && !$this->isCheck();
    }

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

    public function legal(string $sq): array
    {
        return array_values($this->pieceBySq($sq)->legalSqs());
    }

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

    public function toAsciiArray(bool $flip = false): array
    {
        $array = [];
        for ($i = $this->square::SIZE['ranks'] - 1; $i >= 0; $i--) {
            $array[$i] = array_fill(0, $this->square::SIZE['files'], ' . ');
        }
        foreach ($this->pieces() as $piece) {
            list($file, $rank) = AsciiArray::toIndex($piece->sq);
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

    public function diffPieces(array $array1, array $array2): array
    {
        return array_udiff($array2, $array1, function ($b, $a) {
            return $a->sq <=> $b->sq;
        });
    }

    public function clone(): AbstractBoard
    {
        $board = FenToBoardFactory::create($this->toFen(), $this);
        $board->captures = $this->captures;
        $board->history = $this->history;

        return $board;
    }
}
