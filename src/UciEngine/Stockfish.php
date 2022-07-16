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

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function bestMove(string $fen, int $seconds): string
    {
        $bestMove = '(none)';
        $process = proc_open(self::NAME, $this->descr, $this->pipes);
        if (is_resource($process)) {
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
            proc_close($process);
        }

        return $bestMove;
    }

    public function fen(string $fen, int $seconds): string
    {
        $bestMove = $this->bestMove($fen, $seconds);
        if ($bestMove !== '(none)') {
            $process = proc_open(self::NAME, $this->descr, $this->pipes);
            if (is_resource($process)) {
                fwrite($this->pipes[0], "uci\n");
                fwrite($this->pipes[0], "position fen $fen moves $bestMove\n");
                fwrite($this->pipes[0], "go infinite\n");
                sleep($seconds);
                fwrite($this->pipes[0], "stop\n");
                fwrite($this->pipes[0], "d\n");
                fclose($this->pipes[0]);
                while (!feof($this->pipes[1])) {
                    $line = fgets($this->pipes[1]);
                    if (str_starts_with($line, 'Fen: ')) {
                        $fen = substr($line, 5);
                    }
                }
                fclose($this->pipes[1]);
                proc_close($process);
            }
        }

        return $fen;
    }
}
