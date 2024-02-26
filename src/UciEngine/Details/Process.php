<?php

namespace Chess\UciEngine\Details;

use Chess\Exception\ProcessException;

/**
 * Wrapper for handling process's.
 */
class Process
{
    private mixed $process;

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

    public function __construct(string $path)
    {
        $this->process = proc_open($path, $this->descr, $this->pipes);

        if (!is_resource($this->process)) {
            throw new ProcessException();
        }
    }

    public function __destruct()
    {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        proc_close($this->process);
    }

    /**
     * Write a line to the process.
     *
     * @param string $line
     * @return void
     */
    public function writeLine(string $line): void
    {
        fwrite($this->pipes[0], $line . "\n");
    }

    /**
     * Read a line from the process.
     *
     * @return string
     */
    public function readLine(): string
    {
        $line = fgets($this->pipes[1]);

        if ($line === false) {
            throw new ProcessException();
        }

        return $line;
    }

    /**
     * Read until a specific string is found in a line.
     *
     * @param string $needle
     * @return array
     */
    public function readUntil(string $needle): array
    {
        $buffer = [];

        while (!feof($this->pipes[1])) {
            $line = $this->readLine();
            $buffer[] = trim($line);

            if (str_contains($line, $needle)) {
                break;
            }
        }

        return $buffer;
    }

    /**
     * Check if the process is running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        $status = proc_get_status($this->process);

        return $status['running'];
    }
}
