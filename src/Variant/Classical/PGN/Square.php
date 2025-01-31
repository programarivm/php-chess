<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractNotation;

class Square extends AbstractNotation
{
    const REGEX = '[a-h]{1}[1-8]{1}';

    const SIZE = [
        'files' => 8,
        'ranks' => 8,
    ];

    const EXTRACT = '/[^a-h0-9 "\']/';

    public array $all = [];

    public function __construct()
    {
        for ($i = 0; $i < static::SIZE['files']; $i++) {
            for ($j = 0; $j < static::SIZE['ranks']; $j++) {
                $this->all[] = $this->toAlgebraic($i, $j);
            }
        }
    }

    public function validate(string $sq): string
    {
        $file = ord($sq[0]);
        $rank = intval(ltrim($sq, $sq[0]));

        if ($file >= 97 &&
            $file <= 97 + static::SIZE['files'] - 1 &&
            $rank >= 1 &&
            $rank <= static::SIZE['ranks']
        ) {
            return $sq;
        }

        throw new UnknownNotationException();
    }

    public function color(string $sq): string
    {
        $file = $sq[0];
        $rank = substr($sq, 1);

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

    public function corner(): array
    {
        return [
            $this->toAlgebraic(0, 0),
            $this->toAlgebraic(static::SIZE['files'] - 1, 0),
            $this->toAlgebraic(0, static::SIZE['ranks'] - 1),
            $this->toAlgebraic(static::SIZE['files'] - 1, static::SIZE['ranks'] - 1),
        ];
    }

    public function promoRank(string $color): int
    {
        if ($color === Color::W) {
            return static::SIZE['ranks'];
        }

        return 1;
    }

    public function toIndex(string $sq): array
    {
        $j = ord($sq[0]) - 97;
        $i = intval(ltrim($sq, $sq[0])) - 1;

        return [
            $i,
            $j,
        ];
    }

    public function toAlgebraic(int $i, int $j): string
    {
        $file = chr(97 + $i);
        $rank = $j + 1;

        return $file . $rank;
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

        return $sqs;
    }

    public function extract(string $string): string
    {
        return preg_replace(static::EXTRACT, '', $string);
    }

    public function explode(string $string): array
    {
        preg_match_all('/'.static::REGEX.'/', $string, $matches);

        return $matches[0];
    }
}
