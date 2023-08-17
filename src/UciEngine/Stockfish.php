<?php

namespace Chess\UciEngine;

use Chess\Exception\StockfishException;
use Chess\Variant\Classical\Board;

/**
 * Stockfish.
 *
 * PHP wrapper for the Stockfish chess engine.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Stockfish
{
    const FILEPATH = '/usr/games/stockfish';

    const OPTIONS = [
        'Skill Level' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
    ];

    const PARAMS = [
        'depth' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
    ];

    /**
     * PHP Chess board.
     *
     * @var \Chess\Variant\Classical\Board
     */
    private Board $board;

    /**
     * Stockfish filepath.
     *
     * @var string
     */
    private string $filepath;

    /**
     * Process descriptor.
     *
     * @var array
     */
    private array $descr = [
        ['pipe', 'r'],
        ['pipe', 'w'],
    ];

    /**
     * Process pipes.
     *
     * @var array
     */
    private array $pipes = [];

    /**
     * Stockfish options.
     *
     * @var array
     */
    private array $options = [];

    /**
     * Command params.
     *
     * @var array
     */
    private array $params = [];

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     * @param string $filepath
     */
    public function __construct(Board $board, string $filepath = '')
    {
        $this->board = $board;

        $this->filepath = !$filepath ? self::FILEPATH : $filepath;
    }

    /**
     * Returns the PHP Chess board.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * Set Stockfish options.
     *
     * @param array $options
     * @return \Chess\UciEngine\Stockfish
     */
    public function setOptions(array $options): Stockfish
    {
        foreach ($options as $key => $val) {
            if (
                !in_array($key, array_keys(self::OPTIONS)) ||
                !in_array($val, self::OPTIONS[$key])
            ) {
                throw new StockfishException();
            }
        }

        $this->options = $options;

        return $this;
    }

    /**
     * Sets the current command params.
     *
     * @param array $params
     * @return \Chess\UciEngine\Stockfish
     */
    public function setParams(array $params): Stockfish
    {
        foreach ($params as $key => $val) {
            if (
                !in_array($key, array_keys(self::PARAMS)) ||
                !in_array($val, self::PARAMS[$key])
            ) {
                throw new StockfishException();
            }
        }

        $this->params = $params;

        return $this;
    }

    /**
     * Configure Stockfish options.
     */
    private function configure(): void
    {
        foreach ($this->options as $key => $val) {
            fwrite($this->pipes[0], "setoption name $key value $val\n");
        }
    }

    /**
     * Calculates the best move.
     *
     * @param string $fen
     * @return string
     */
    public function bestMove(string $fen): string
    {
        $bestMove = '(none)';
        $process = proc_open($this->filepath, $this->descr, $this->pipes);
        if (is_resource($process)) {
            fwrite($this->pipes[0], "uci\n");
            fwrite($this->pipes[0], "ucinewgame\n");
            $this->configure();
            fwrite($this->pipes[0], "position fen $fen\n");
            fwrite($this->pipes[0], "go depth {$this->params['depth']}\n");
            while (!feof($this->pipes[1])) {
                $line = fgets($this->pipes[1]);
                if (str_starts_with($line, 'bestmove')) {
                    $exploded = explode(' ', $line);
                    $bestMove = $exploded[1];
                    fclose($this->pipes[0]);
                }
            }
            fclose($this->pipes[1]);
            proc_close($process);
        }

        return $bestMove;
    }

    /**
     * Returns the best move in LAN format.
     *
     * @param string $fen
     * @return string
     */
    public function play(string $fen): string
    {
        $bestMove = $this->bestMove($fen);
        if ($bestMove !== '(none)') {
            $process = proc_open($this->filepath, $this->descr, $this->pipes);
            if (is_resource($process)) {
                fwrite($this->pipes[0], "uci\n");
                fwrite($this->pipes[0], "position fen $fen moves $bestMove\n");
                fwrite($this->pipes[0], "d\n");
                fclose($this->pipes[0]);
                fclose($this->pipes[1]);
                proc_close($process);
            }
        }

        return $bestMove;
    }

    /**
     * Returns the evaluation of the current position.
     *
     * @param string $fen
     * @return float
     */
    public function eval(string $fen): float
    {
        $eval = '(none)';
        $process = proc_open($this->filepath, $this->descr, $this->pipes);
        if (is_resource($process)) {
            fwrite($this->pipes[0], "uci\n");
            fwrite($this->pipes[0], "ucinewgame\n");
            $this->configure();
            fwrite($this->pipes[0], "position fen $fen\n");
            fwrite($this->pipes[0], "eval\n");
            while (!feof($this->pipes[1])) {
                $line = fgets($this->pipes[1]);
                if (str_starts_with($line, 'Total evaluation:')) {
                    $exploded = explode(' ', $line);
                    $eval = $exploded[2];
                    fclose($this->pipes[0]);
                }
            }
            fclose($this->pipes[1]);
            proc_close($process);
        }

        return floatval($eval);
    }
}
