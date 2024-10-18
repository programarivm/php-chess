<?php

namespace Chess\UciEngine\Details;

use JsonSerializable;
use Chess\UciEngine\Details\Score;
use Chess\UciEngine\Details\Type;

/**
 * UCI Info Line Structure for handling the analysis info.
 */
class UciInfoLine implements JsonSerializable
{
    /**
     * Depth of the search.
     *
     * @var int|null
     */
    public ?int $depth = null;

    /**
     * Selective depth of the search.
     *
     * @var int|null
     */
    public ?int $seldepth = null;

    /**
     * MultiPV value.
     *
     * @var int|null
     */
    public ?int $multipv = null;

    /**
     * Score of the search.
     *
     * @var Score|null
     */
    public ?Score $score = null;

    /**
     * Nodes searched.
     *
     * @var int|null
     */
    public ?int $nodes = null;

    /**
     * Nodes per second.
     *
     * @var int|null
     */
    public ?int $nps = null;

    /**
     * Hashfull value.
     *
     * @var int|null
     */
    public ?int $hashfull = null;

    /**
     * TBHits value.
     *
     * @var int|null
     */
    public ?int $tbhits = null;

    /**
     * Time searched in milliseconds.
     *
     * @var int|null
     */
    public ?int $time = null;

    /**
     * Principal variation.
     *
     * @var array|null
     */
    public ?array $pv = null;

    public function __construct(string $line)
    {
        $this->parseLine($line);
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * Combine multiple UciInfoLine together, the returned
     * object will contain the latest data for each available key.
     *
     * @param array $infoLines
     * @return UciInfoLine
     */
    public static function aggregateInfo(array $infoLines): UciInfoLine
    {
        $combined = new UciInfoLine('');

        foreach ($infoLines as $infoLine) {
            $infoLine = new UciInfoLine($infoLine);

            foreach (get_object_vars($infoLine) as $key => $value) {
                if ($value !== null) {
                    $combined->$key = $value;
                }
            }
        }

        return $combined;
    }

    /**
     * Extract the information from the line.
     *
     * @param string $line
     * @return void
     */
    private function parseLine(string $line): void
    {
        $parts = explode(' ', trim($line));

        for ($i = 0; $i < count($parts); $i++) {
            $nextPart = $parts[$i + 1] ?? null;
            switch ($parts[$i]) {
                case 'depth':
                    $this->depth = (int)$nextPart;
                    break;
                case 'seldepth':
                    $this->seldepth = (int)$nextPart;
                    break;
                case 'multipv':
                    $this->multipv = (int)$nextPart;
                    break;
                case 'score':
                    // Check if next is 'cp' or 'mate', then assign appropriately
                    if (isset($parts[$i + 2])) {
                        $type = $nextPart === 'cp' ? Type::CP : Type::MATE;
                        $this->score = new Score((int)$parts[$i + 2], $type);
                    }
                    $i++; // Skip next part since it's part of the score
                    break;
                case 'nodes':
                    $this->nodes = (int)$nextPart;
                    break;
                case 'nps':
                    $this->nps = (int)$nextPart;
                    break;
                case 'hashfull':
                    $this->hashfull = (int)$nextPart;
                    break;
                case 'tbhits':
                    $this->tbhits = (int)$nextPart;
                    break;
                case 'time':
                    $this->time = (int)$nextPart;
                    break;
                case 'pv':
                    // Start collecting pv parts from this point
                    for ($j = $i + 1; $j < count($parts); $j++) {
                        if ($this->isUciMove($parts[$j])) {
                            $pvParts[] = $parts[$j];
                        } else {
                            // If a part is not a UCI move, it might be the start of a new info field
                            break;
                        }
                    }
                    // Adjust $i since we've moved ahead in the array
                    $i = $j - 1;
                    break;
            }
        }

        if (!empty($pvParts)) {
            $this->pv = $pvParts;
        }
    }

    /**
     * Checks if a string has a valid uci move format.
     *
     * @param string $move
     * @return bool
     */
    private function isUciMove(string $move): bool
    {
        if (strlen($move) < 4) {
            return false;
        }

        $isFile = fn($c) => $c >= 'a' && $c <= 'h';
        $isDigit = fn($c) => $c >= '1' && $c <= '8';
        $isPromotion = fn($c) => in_array($c, ['n', 'b', 'r', 'q']);

        $isUci = $isFile($move[0]) && $isDigit($move[1]) && $isFile($move[2]) && $isDigit($move[3]);

        if (strlen($move) == 5) {
            $isUci = $isUci && $isPromotion($move[4]);
        }

        return $isUci;
    }
}
