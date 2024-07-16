<?php

namespace Chess\Variant;

abstract class AbstractNotation
{
    public function values(): array
    {
        return (new \ReflectionClass(get_called_class()))->getConstants();
    }
}
