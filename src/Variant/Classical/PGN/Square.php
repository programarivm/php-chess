<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractNotation;

class Square extends AbstractNotation
{
    /**
     * Regular expression representing a square in algebraic notation.
     *
     * @var string
     */
    const AN = '[a-h]{1}[1-8]{1}';

    /**
     * Regular expression representing a square for further extraction from strings.
     *
     * @var string
     */
    const EXTRACT = '/[^a-h0-9 "\']/';

    /**
     * The size of the chess board.
     *
     * @var array
     */
    const SIZE = [
        'files' => 8,
        'ranks' => 8,
    ];

    /**
     * All squares of the chess board.
     *
     * @var array
     */
    public array $all = [];

    public function __construct()
    {
        for ($i = 0; $i < static::SIZE['files']; $i++) {
            for ($j = 0; $j < static::SIZE['ranks']; $j++) {
                $this->all[] = $this->toAn($i, $j);
            }
        }
    }

    /**
     * Validate a square in algebraic notation.
     * 
     * @param string $sq
     * @throws \Chess\Exception\UnknownNotationException
     * @return string
     */
    public function validate(string $sq): string
    {
        $file = ord($sq[0]);
        $rank = (int) substr($sq, 1);
        if ($file >= 97 &&
            $file <= 97 + static::SIZE['files'] - 1 &&
            $rank >= 1 &&
            $rank <= static::SIZE['ranks']
        ) {
            return $sq;
        }

        throw new UnknownNotationException();
    }

    /**
     * Returns the color of the given square.
     * 
     * @param string $sq
     * @return string
     */
    public function color(string $sq): string
    {
        $file = $sq[0];
        $rank = (int) substr($sq, 1);
        if ((ord($file) - 97) % 2 === 0) {
            if ($rank % 2 !== 0) {
                return Color::B;
            }
        } else {
            if ($rank % 2 === 0) {
                return Color::B;
            }
        }

        return Color::W;
    }

    /**
     * Returns the corner of the chess board.
     * 
     * @return array
     */
    public function corner(): array
    {
        return [
            $this->toAn(0, 0),
            $this->toAn(static::SIZE['files'] - 1, 0),
            $this->toAn(0, static::SIZE['ranks'] - 1),
            $this->toAn(static::SIZE['files'] - 1, static::SIZE['ranks'] - 1),
        ];
    }

    /**
     * Returns the promotion rank.
     * 
     * @param string $color
     * @return int
     */
    public function promoRank(string $color): int
    {
        if ($color === Color::W) {
            return static::SIZE['ranks'];
        }

        return 1;
    }

    /**
     * Converts a square in algebraic notation to a pair of indices i and j.
     * 
     * @param string $sq
     * @return array
     */
    public function toIndices(string $sq): array
    {
        return [
            (int) substr($sq, 1) - 1,
            ord($sq[0]) - 97,
        ];
    }

    /**
     * Converts a pair of indices i and j to a square in algebraic notation.
     * 
     * @param int $i
     * @param int $j
     * @return string
     */
    public function toAn(int $i, int $j): string
    {
        return chr(97 + $i) . $j + 1;
    }

    /**
     * Returns the squares found between the two endpoints of a line segment.
     * 
     * @param string $a
     * @param string $b
     * @return array
     */
    public function line(string $a, string $b): array
    {
        $sqs = [];
        $aFile = $a[0];
        $aRank = (int) substr($a, 1);
        $bFile = $b[0];
        $bRank = (int) substr($b, 1);
        if ($aFile === $bFile) {
            if ($aRank > $bRank) {
                for ($i = 1; $i < $aRank - $bRank; $i++) {
                    $sqs[] = $aFile . ($bRank + $i);
                }
            } else {
                for ($i = 1; $i < $bRank - $aRank; $i++) {
                    $sqs[] = $aFile . ($bRank - $i);
                }
            }
        } elseif ($aRank === $bRank) {
            if ($aFile > $bFile) {
                for ($i = 1; $i < ord($aFile) - ord($bFile); $i++) {
                    $sqs[] = chr(ord($bFile) + $i) . $aRank;
                }
            } else {
                for ($i = 1; $i < ord($bFile) - ord($aFile); $i++) {
                    $sqs[] = chr(ord($bFile) - $i) . $aRank;
                }
            }
        } elseif (abs(ord($aFile) - ord($bFile)) === abs(ord($aRank) - ord($bRank))) {
            if ($aFile > $bFile && $aRank < $bRank) {
                for ($i = 1; $i < $bRank - $aRank; $i++) {
                    $sqs[] = chr(ord($bFile) + $i) . ($bRank - $i);
                }
            } elseif ($aFile < $bFile && $aRank < $bRank) {
                for ($i = 1; $i < $bRank - $aRank; $i++) {
                    $sqs[] = chr(ord($bFile) - $i) . ($bRank - $i);
                }
            } elseif ($aFile < $bFile && $aRank > $bRank) {
                for ($i = 1; $i < $aRank - $bRank; $i++) {
                    $sqs[] = chr(ord($bFile) - $i) . ($bRank + $i);
                }
            } else {
                for ($i = 1; $i < $aRank - $bRank; $i++) {
                    $sqs[] = chr(ord($bFile) + $i) . ($bRank + $i);
                }
            }
        }
        sort($sqs);

        return $sqs;
    }

    /**
     * Returns true if the B square is between the given two squares A and C.
     *
     * @param string $a
     * @param string $b
     * @param string $c
     * @return bool
     */
    public function isBetweenSqs(string $a, string $b, string $c): bool
    {
        return in_array($b, $this->line($a, $c));
    }

    /**
     * Extract squares from a string.
     * 
     * @param string $string
     * @return string
     */
    public function substr(string $string): string
    {
        return preg_replace(static::EXTRACT, '', $string);
    }

    /**
     * Explode squares from a string.
     * 
     * @param string $string
     * @return array
     */
    public function explode(string $string): array
    {
        preg_match_all('/' . static::AN . '/', $string, $matches);

        return $matches[0];
    }
}
