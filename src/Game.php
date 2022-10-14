<?php

namespace Chess;

use Chess\Grandmaster;
use Chess\ML\Supervised\Regression\GeometricSumPredictor;
use Chess\Player\FenPlayer;
use Chess\UciEngine\Stockfish;
use Chess\Variant\Classical\FEN\BoardToStr;
use Chess\Variant\Classical\FEN\ShortStrToPgn;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Variant\Capablanca80\Board as Capablanca80Board;
use Chess\Variant\Capablanca100\Board as Capablanca100Board;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Chess960\StartPosition;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

/**
 * Game
 *
 * Game is the main component of the PHP Chess Server. It is a wrapper for the
 * Chess\Board object to play chess online but it is also used on command line
 * (CLI) apps as well as in APIs.
 *
 * @author Jordi Bassagañas
 * @license GPL
 * @link https://github.com/chesslablab/chess-server
 */
class Game
{
    const VARIANT_960               = '960';
    const VARIANT_CAPABLANCA_80     = 'capablanca80';
    const VARIANT_CAPABLANCA_100    = 'capablanca100';
    const VARIANT_CLASSICAL         = 'classical';

    const MODE_ANALYSIS             = 'analysis';
    const MODE_GM                   = 'gm';
    const MODE_FEN                  = 'fen';
    const MODE_PGN                  = 'pgn';
    const MODE_PLAY                 = 'play';
    const MODE_RUBIX                = 'rubix';
    const MODE_STOCKFISH            = 'stockfish';

    const ML_FOLDER                 = __DIR__.'/../ml/';
    const ML_FILE                   = 'regression/checkmate_king_and_rook_vs_king.rbx';

    /**
     * Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    private ClassicalBoard $board;

    /**
     * Variant.
     *
     * @var string
     */
    private string $variant;

    /**
     * Mode.
     *
     * @var string
     */
    private string $mode;

    /**
     * Grandmaster.
     *
     * @var Grandmaster
     */
    private null|Grandmaster $gm;

    public function __construct(
        string $variant,
        string $mode,
        null|Grandmaster $gm = null
    ) {
        $this->variant = $variant;
        $this->mode = $mode;
        $this->gm = $gm;

        if ($this->variant === self::VARIANT_960) {
            $startPos = (new StartPosition())->create();
            $this->board = new Chess960Board($startPos);
        } elseif ($this->variant === self::VARIANT_CAPABLANCA_80) {
            $this->board = new Capablanca80Board();
        } elseif ($this->variant === self::VARIANT_CAPABLANCA_100) {
            $this->board = new Capablanca100Board();
        } elseif ($this->variant === self::VARIANT_CLASSICAL) {
            $this->board = new ClassicalBoard();
        }
    }

    /**
     * Returns the Chess\Board object.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function getBoard(): ClassicalBoard
    {
        return $this->board;
    }

    /**
     * Returns the game variant.
     *
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * Returns the game mode.
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Sets the Chess\Board object.
     *
     * @param \Chess\Variant\Classical\Board $board
     * @return \Chess\Game
     */
    public function setBoard(ClassicalBoard $board): Game
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Returns the state of the board.
     *
     * @return object
     */
    public function state(): object
    {
        $history = $this->board->getHistory();
        $end = end($history);

        return (object) [
            'turn' => $this->board->getTurn(),
            'pgn' => $end ? $end->move->pgn : null,
            'castlingAbility' => $this->board->getCastlingAbility(),
            'movetext' => $this->board->getMovetext(),
            'fen' => $this->board->toFen(),
            'isCheck' => $this->board->isCheck(),
            'isMate' => $this->board->isMate(),
            'isStalemate' => $this->board->isStalemate(),
            'mode' => $this->getMode(),
        ];
    }

    /**
     * Makes a move.
     *
     * @param string $color
     * @param string $pgn
     * @return bool true if the move can be made; otherwise false
     */
    public function play(string $color, string $pgn): bool
    {
        return $this->board->play($color, $pgn);
    }

    /**
     * Returns a computer generated response to the current position.
     *
     * @param array $options
     * @param array $params
     * @return object|null
     */
    public function ai(array $options = [], array $params = []): ?object
    {
        if ($this->mode === Game::MODE_GM) {
            return $this->gm->move($this);
        } else if ($this->mode === Game::MODE_RUBIX) {
            if ($this->gm) {
                if ($move = $this->gm->move($this)) {
                    return $move;
                }
            }
            $estimator = PersistentModel::load(
                new Filesystem(self::ML_FOLDER.self::ML_FILE)
            );
            $move = (new GeometricSumPredictor(
                $this->board,
                $estimator
            ))->predict();
            return (object) [
                'move' => $move,
            ];
        }

        if ($this->gm) {
            if ($move = $this->gm->move($this)) {
                return $move;
            }
        }

        $stockfish = (new Stockfish($this->board))
            ->setOptions($options)
            ->setParams($params);

        $fromFen = $this->board->toFen();
        $toFen = $stockfish->shortFen($fromFen);
        $pgn = (new ShortStrToPgn($fromFen, $toFen))->create();

        return (object) [
            'move' => current($pgn),
        ];
    }

    /**
     * Loads a FEN string allowing to continue a chess game.
     *
     * @param string $string
     */
    public function loadFen(string $string): void
    {
        $this->board = (new StrToBoard($string))->create();
    }

    /**
     * Loads a PGN movetext allowing to continue a chess game.
     *
     * @param string $movetext
     */
    public function loadPgn(string $movetext): void
    {
        $this->board = (new Player($movetext))->play()->getBoard();
    }

    /**
     * Makes a move in short FEN format. Only the piece placement and the side
     * to move are required.
     *
     * @param string $toShortFen
     * @return bool|string
     */
    public function playFen(string $toShortFen): bool|string
    {
        $fromFen = (new BoardToStr($this->board))->create();
        $fromPiecePlacement = explode(' ', $fromFen)[0];
        $toPiecePlacement = explode(' ', $toShortFen)[0];

        $player = new FenPlayer($this->board);

        if ($this->variant === self::VARIANT_960) {
            return $player->chess960(
                $fromFen,
                $toShortFen,
                $fromPiecePlacement,
                $toPiecePlacement
            );
        } elseif ($this->variant === self::VARIANT_CAPABLANCA_80) {
            return $player->capablanca80(
                $fromFen,
                $toShortFen,
                $fromPiecePlacement,
                $toPiecePlacement
            );
        } elseif ($this->variant === self::VARIANT_CAPABLANCA_100) {
            return $player->capablanca100(
                $fromFen,
                $toShortFen,
                $fromPiecePlacement,
                $toPiecePlacement
            );
        } elseif ($this->variant === self::VARIANT_CLASSICAL) {
            return $player->classical(
                $fromFen,
                $toShortFen,
                $fromPiecePlacement,
                $toPiecePlacement
            );
        }

        return false;
    }
}
