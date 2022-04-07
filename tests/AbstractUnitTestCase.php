<?php

namespace Chess\Tests;

use PHPUnit\Framework\TestCase;

class AbstractUnitTestCase extends TestCase
{
    const DATA_FOLDER = __DIR__.'/data';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
