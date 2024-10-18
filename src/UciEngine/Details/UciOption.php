<?php

namespace Chess\UciEngine\Details;

/**
 * UCI Option Structure for handling the engine options.
 */
class UciOption
{
    public string $name;
    public string $type;
    public string $default;
    public string $min;
    public string $max;

    public mixed $value;

    public function __construct(string $name, string $type, string $default, string $min, string $max)
    {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Creates an UciOption object from a uci info line.
     *
     * @param string $line
     * @return UciOption
     */
    public static function createFromLine(string $line): UciOption
    {
        // uci options are structured like this:
        // option name {name} type {check|spin|combo|button|string}
        // default {default} [min {min} max {max}] [var {var1} var {var2} ...]
        // uci options can include arbitrary spaces in the name, so we need to
        // account for that, and parse the line accordingly to the format and
        // skip whitespaces
        // todo: add support for the 'var' field

        $parts = explode(' ', $line);
        $result = [];
        $currentKey = '';
        $collectingValue = false;
        $value = '';

        foreach ($parts as $part) {
            if ($part === "") {
                continue; // skip empty parts
            }

            if (in_array($part, ['name', 'type', 'default', 'min', 'max', 'var'])) {
                if ($collectingValue) {
                    $result[$currentKey] = $value;
                    $value = '';
                }
                $currentKey = $part;
                $collectingValue = true;
            } elseif ($collectingValue) {
                $value .= ' ' . $part;
            }
        }

        // Check if there's a value left to be added to the result
        if ($collectingValue) {
            $result[$currentKey] = trim($value);
        }

        // Handling the special case for 'default' when it is empty
        if (!array_key_exists('default', $result)) {
            $result['default'] = '';
        }

        $result = array_map('trim', $result);

        return new self(
            $result['name'],
            $result['type'],
            $result['default'],
            $result['min'] ?? '',
            $result['max'] ?? ''
        );
    }
}
