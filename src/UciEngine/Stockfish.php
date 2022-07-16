<?php

namespace Chess\UciEngine;

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

    protected $descr = [
        ['pipe', 'r'],
        ['pipe', 'w'],
    ];

    protected $pipes = [];

    protected $process;

    public function __construct() {
        $this->process = proc_open(self::NAME, $this->descr, $this->pipes);
    }

    public function bestMove(string $moves, int $seconds)
    {
        $bestMove = '';
        if (is_resource($this->process)) {
            fwrite($this->pipes[0], "uci\n");
            fwrite($this->pipes[0], "position startpos move $moves\n");
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
}
