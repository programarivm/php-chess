<?php

namespace Chess\UciEngine;

use Chess\UciEngine\Details\Limit;
use Chess\UciEngine\Details\Process;
use Chess\UciEngine\Details\UCIInfoLine;
use Chess\UciEngine\Details\UCIOption;
use Chess\Variant\Classical\Board;

class Stockfish
{
    const PATH = '/usr/games/stockfish';

    /**
     * Process for the engine.
     *
     * @var Process
     */
    private Process $process;

    /**
     * Array of UCIOptions
     *
     * @var array
     */
    private array $options;

    public function __construct(string $path = '')
    {
        $this->process = new Process(!$path ? self::PATH : $path);

        $this->process->writeLine('uci');
        $this->process->readUntil('uciok');

        $this->process->writeLine('isready');
        $this->process->readUntil('readyok');

        $this->options = $this->readOptions();
    }

    public function __destruct()
    {
        $this->process->writeLine('quit');
    }

    /**
     * Returns an array of key value pairs with the value
     * being the UCIOption and the key being the name of the option.
     *
     * @return array
     */
    private function readOptions(): array
    {
        $this->process->writeLine('uci');

        $options = [];

        while (true) {
            $line = $this->process->readLine();

            if (str_contains($line, 'uciok')) {
                break;
            }

            if (str_contains($line, 'option')) {
                $option = UCIOption::createFromLine($line);
                $options[$option->name] = $option;
            }
        }

        return $options;
    }

    /**
     * Get current UCIOptions
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set an uci option for the engine.
     *
     * @param string $name
     * @param string $value
     * @return \Chess\UciEngine\Stockfish
     */
    public function setOption(string $name, string $value): Stockfish
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException("Option $name does not exist");
        }

        if ($value === '') {
            $this->process->writeLine("setoption name $name");
        }

        $this->options[$name]->value = $value;

        $this->process->writeLine("setoption name $name value $value");

        return $this;
    }

    /**
     * Analyse the board with the given limit and
     * return the bestmove and all uci info lines.
     *
     * @param Board $board
     * @param Limit $limit
     * @return array
     */
    public function analyse(Board $board, Limit $limit): array
    {
        $this->process->writeLine("position fen " . $board->toFen());
        $this->process->writeLine($this->buildGoCommand($limit));
        $output = $this->process->readUntil('bestmove');

        return [
            "bestmove" => explode(' ', end($output))[1],
            "info" => array_map(function ($line) {
                return new UCIInfoLine($line);
            }, $output)
        ];
    }

    /**
     * Sends the ucinewgame command to the engine. Does not reset the options.
     *
     * @return void
     */
    public function newGame(): void
    {
        $this->process->writeLine("ucinewgame");
    }

    /**
     * Reset all options to their default values.
     *
     * @return void
     */
    public function resetOptions(): void
    {
        foreach ($this->options as $option) {
            $this->setOption($option->name, $option->default);
        }
    }

    /**
     * Build the go command based on the given limit.
     *
     * @param Limit $limit
     * @return string
     */
    private function buildGoCommand(Limit $limit): string
    {
        $command = 'go';

        if ($limit->time !== null) {
            $command .= ' movetime ' . $limit->time;
        }

        if ($limit->depth !== null) {
            $command .= ' depth ' . $limit->depth;
        }

        if ($limit->nodes !== null) {
            $command .= ' nodes ' . $limit->nodes;
        }

        if ($limit->mate !== null) {
            $command .= ' mate ' . $limit->mate;
        }

        if ($limit->white_clock !== null) {
            $command .= ' wtime ' . $limit->white_clock;
        }

        if ($limit->black_clock !== null) {
            $command .= ' btime ' . $limit->black_clock;
        }

        if ($limit->white_inc !== null) {
            $command .= ' winc ' . $limit->white_inc;
        }

        if ($limit->black_inc !== null) {
            $command .= ' binc ' . $limit->black_inc;
        }

        if ($limit->remaining_moves !== null) {
            $command .= ' movestogo ' . $limit->remaining_moves;
        }

        return $command;
    }
}
