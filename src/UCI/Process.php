<?php

namespace Chess\UCI;

/**
 * UCI engine process.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Process
{
    protected $descr = [
        ['pipe', 'r'],
        ['pipe', 'w'],
    ];

    protected $pipes = [];

    protected $process;

    public function __construct(string $name) {
        $this->process = proc_open($name, $this->descr, $this->pipes);
    }
}
