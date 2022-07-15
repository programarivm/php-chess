<?php

namespace Chess\UCI;

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
    public function bestMove(string $moves, int $seconds)
    {
        $bestMove = '';
        $descr = [
            ['pipe', 'r'],
            ['pipe', 'w'],
        ];
        $pipes = [];
        $process = proc_open('stockfish', $descr, $pipes);
        if (is_resource($process)) {
            fwrite($pipes[0], "uci\n");
            fwrite($pipes[0], "position startpos move $moves\n");
            fwrite($pipes[0], "go infinite\n");
            sleep($seconds);
            fwrite($pipes[0], "stop\n");
            fclose($pipes[0]);
            while (!feof($pipes[1])) {
                $line = fgets($pipes[1]);
                if (str_starts_with($line, 'bestmove')) {
                    $exploded = explode(' ', $line);
                    $bestMove = $exploded[1];
                }
            }
            fclose($pipes[1]);
            proc_close($process);
        }

        return $bestMove;
    }
}
