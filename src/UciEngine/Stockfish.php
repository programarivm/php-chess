<?php

namespace Chess\UciEngine;

use Chess\Board;

/**
 * Stockfish.
 *
 * PHP wrapper for the Stockfish chess engine.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Stockfish
{
    const NAME = 'stockfish';

    protected Board $board;

    protected array $descr = [
        ['pipe', 'r'],
        ['pipe', 'w'],
    ];

    protected array $pipes = [];

    protected $process;

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->process = proc_open(self::NAME, $this->descr, $this->pipes);
    }

    public function bestMove(string $fen, int $seconds): string
    {
        echo $fen . PHP_EOL;
        $bestMove = '(none)';
        if (is_resource($this->process)) {
            fwrite($this->pipes[0], "uci\n");
            fwrite($this->pipes[0], "position fen $fen\n");
            fwrite($this->pipes[0], "go infinite\n");
            sleep($seconds);
            fwrite($this->pipes[0], "stop\n");
            fclose($this->pipes[0]);
            while (!feof($this->pipes[1])) {
                $line = fgets($this->pipes[1]);
                if (str_starts_with($line, 'bestmove')) {
                    $exploded = explode(' ', $line);
                    $bestMove = $exploded[1];
                }
            }
            fclose($this->pipes[1]);
            proc_close($this->process);
        }

        return $bestMove;
    }

    public function play(string $move): Stockfish
    {
        if ($move !== '(none)') {
            $this->board->play($this->board->getTurn(), $this->fromLongAnToAn($move));
        }

        return $this;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }
}
