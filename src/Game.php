<?php

namespace Chess;

use Chess\Ascii;
use Chess\Heuristics;
use Chess\Player;
use Chess\FEN\BoardToStr;
use Chess\FEN\ShortStrToPgn;
use Chess\FEN\StrToBoard;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\ML\Supervised\Regression\GeometricSumPredictor;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

/**
 * Game
 *
 * Wrapper for the Chess\Board class. It is the main component of the PHP Chess Server.
 * There is a one-to-one correspondence between the Chess\Game methods and
 * the commands available in the ChessServer\Command namespace.
 *
 * @author Jordi Bassagañas
 * @license GPL
 * @link https://github.com/chesslablab/chess-server
 */
class Game
{
    const MODE_AI           = 'MODE_AI';
    const MODE_ANALYSIS     = 'MODE_ANALYSIS';
    const MODE_GRANDMASTER  = 'MODE_GRANDMASTER';
    const MODE_LOAD_FEN     = 'MODE_LOAD_FEN';
    const MODE_LOAD_PGN     = 'MODE_LOAD_PGN';
    const MODE_PLAY_FRIEND  = 'MODE_PLAY_FRIEND';

    const MODEL_FOLDER = __DIR__.'/../model/';

    /**
     * Chess board.
     *
     * @var Board
     */
    private $board;

    /**
     * Mode.
     *
     * @var string
     */
    private $mode;

    /**
     * Estimator.
     *
     * @var PersistentModel
     */
    private $estimator;

    /**
     * Constructor.
     */
    public function __construct(string $mode = null, string $model = null)
    {
        $this->board = new Board();
        $this->mode = $mode ?? self::MODE_ANALYSIS;
        if ($model) {
            $this->estimator = PersistentModel::load(new Filesystem(self::MODEL_FOLDER.$model));
        }
    }

    /**
     * Gets the board's status.
     *
     * @return \stdClass
     */
    public function status(): \stdClass
    {
        return (object) [
            'castle' => $this->board->getCastle(),
            'isCheck' => $this->board->isCheck(),
            'isMate' => $this->board->isMate(),
            'movetext' => $this->board->getMovetext(),
            'turn' => $this->board->getTurn(),
        ];
    }

    public function castle(): ?array
    {
        return $this->board->getCastle();
    }

    /**
     * Gets the history.
     *
     * @return mixed null|array
     */
    public function history(): ?array
    {
        $history = [];

        $boardHistory = $this->board->getHistory();

        foreach ($boardHistory as $entry) {
            $history[] = (object) [
                'pgn' => $entry->move->pgn,
                'color' => $entry->move->color,
                'id' => $entry->move->id,
                'sq' => $entry->sq,
                'isCapture' => $entry->move->isCapture,
                'isCheck' => $entry->move->isCheck,
            ];
        }

        return $history;
    }

    /**
     * Gets the movetext.
     *
     * @return string
     */
    public function movetext(): string
    {
        return $this->board->getMovetext();
    }

    /**
     * Gets the pieces captured by both players.
     *
     * @return mixed null|array
     */
    public function captures(): ?array
    {
        return $this->board->getCaptures();
    }

    /**
     * Gets the pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function pieces(string $color): array
    {
        $result = [];

        $pieces = $this->board->getPiecesByColor(Validate::color($color));

        foreach ($pieces as $piece) {
            $result[] = (object) [
                'id' => $piece->getId(),
                'sq' => $piece->getSquare(),
                'moves' => $piece->getSqs(),
            ];
        }

        return $result;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $sq
     * @return mixed null|\stdClass
     */
    public function piece(string $sq): ?\stdClass
    {
        if ($piece = $this->board->getPieceBySq(Validate::sq($sq))) {
            $moves = [];
            $color = $piece->getColor();
            foreach ($piece->getSqs() as $sq) {
                $clone = unserialize(serialize($this->board));
                switch ($piece->getId()) {
                    case Symbol::K:
                        if ($clone->play($color, Symbol::K.$sq)) {
                            $moves[] = $sq;
                        } elseif ($clone->play($color, Symbol::K.'x'.$sq)) {
                            $moves[] = $sq;
                        }
                        break;
                    case Symbol::P:
                        if ($clone->play($color, $piece->getFile()."x$sq")) {
                            $moves[] = $sq;
                        } elseif ($clone->play($color, $sq)) {
                            $moves[] = $sq;
                        }
                        break;
                    default:
                        if ($clone->play($color, $piece->getId().$sq)) {
                            $moves[] = $sq;
                        } elseif ($clone->play($color, "{$piece->getId()}x$sq")) {
                            $moves[] = $sq;
                        }
                        break;
                }
            }
            $result = [
                'color' => $color,
                'id' => $piece->getId(),
                'sq' => $piece->getSquare(),
                'moves' => $moves,
            ];
            if ($piece->getId() === Symbol::P) {
                if ($enPassant = $piece->getEnPassantSq()) {
                    $result['enPassant'] = $enPassant;
                }
            }
            return (object) $result;
        }

        return null;
    }

    /**
     * Calculates whether the current player is checked.
     *
     * @return bool
     */
    public function isCheck(): bool
    {
        return $this->board->isCheck();
    }

    /**
     * Calculates whether the current player is mated.
     *
     * @return bool
     */
    public function isMate(): bool
    {
        return $this->board->isMate();
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
     * Computer response to the current position.
     *
     * @return string
     */
    public function response()
    {
        $response = (new Grandmaster())->response($this->board->getMovetext());

        if ($this->mode === Game::MODE_AI) {
            if ($response) {
                return $response;
            } else {
                $response = (new GeometricSumPredictor($this->board, $this->estimator))->predict();
                return $response;
            }
        } elseif ($this->mode === Game::MODE_GRANDMASTER) {
            return $response;
        }

        return null;
    }

    public function ascii(): string
    {
        return (new Ascii())->print($this->board);
    }

    public function fen(): string
    {
        return (new BoardToStr($this->board))->create();
    }

    /**
     * Loads a FEN string allowing to continue a chess game.
     *
     * @param string
     */
    public function loadFen(string $string): void
    {
        $this->board = (new StrToBoard($string))->create();
    }

    /**
     * Loads a PGN movetext allowing to continue a chess game.
     *
     * @param string
     */
    public function loadPgn(string $movetext)
    {
        $this->board = (new Player($movetext))->play()->getBoard();
    }

    /**
     * Makes a move in short FEN format.
     *
     * Only the piece placement and the side to move are required.
     *
     * @param string $toShortFen
     * @return mixed
     */
    public function playFen(string $toShortFen)
    {
        $fromFen = (new BoardToStr($this->board))->create();

        $fromPiecePlacement = explode(' ', $fromFen)[0];
        $toPiecePlacement = explode(' ', $toShortFen)[0];
        $fromRanks = explode('/', $fromPiecePlacement);
        $toRanks = explode('/', $toPiecePlacement);

        if (
          'K2R' === substr($fromRanks[7], -3) &&
          'KR' === substr($toRanks[7], -2) &&
          $this->board->play(Symbol::WHITE, Symbol::O_O)
        ) {
            return Symbol::O_O;
        } elseif (
          'R3K' === substr($fromRanks[7], 0, 3) &&
          'R1K' === substr($toRanks[7], 0, 3) &&
          $this->board->play(Symbol::WHITE, Symbol::O_O_O)
        ) {
            return Symbol::O_O_O;
        } elseif (
          'k2r' === substr($fromRanks[0], -3) &&
          'kr' === substr($toRanks[0], -2) &&
          $this->board->play(Symbol::BLACK, Symbol::O_O)
        ) {
            return Symbol::O_O;
        } elseif (
          'r3k' === substr($fromRanks[0], 0, 3) &&
          'r1k' === substr($toRanks[0], 0, 3) &&
          $this->board->play(Symbol::BLACK, Symbol::O_O_O)
        ) {
            return Symbol::O_O_O;
        }

        $pgn = (new ShortStrToPgn($fromFen, $toShortFen))->create();
        $color = key($pgn);
        $result = current($pgn);

        if ($result) {
            $clone = unserialize(serialize($this->board));
            $clone->play($color, $result);
            $clone->isMate() ? $check = '#' : ($clone->isCheck() ? $check = '+' : $check = '');
            return $this->board->play($color, $result.$check);
        }

        return false;
    }

    public function heuristics($balanced = false, $fen = ''): array
    {
        $movetext = $this->board->getMovetext();

        if ($this->mode === self::MODE_LOAD_FEN) {
            $board = (new StrToBoard($fen))->create();
            $heuristics = new Heuristics($movetext, $board);
        } else {
            $heuristics = new Heuristics($movetext);
        }

        if ($balanced) {
            return $heuristics->getBalance();
        }

        return $heuristics->getResult();
    }

    public function undoMove(): ?\stdClass
    {
        if ($this->board->getHistory()) {
            $this->board->undoMove($this->board->getCastle());
            return $this->status();
        }

        return null;
    }
}
