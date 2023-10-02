<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

trait RandomCastlingRuleTrait
{
    protected array $startPos;

    protected array $startFiles;
    
    protected array $size;

    protected function sq()
    {
        $longCastlingRook = false;
        foreach ($this->startPos as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $this->size['files'];
            if ($val === Piece::R) {
                if (!$longCastlingRook) {
                    $this->rule[Color::W][Piece::R][Castle::LONG]['sq']['current'] = $wSq;
                    $this->rule[Color::B][Piece::R][Castle::LONG]['sq']['current'] = $bSq;
                    $longCastlingRook = true;
                } else {
                    $this->rule[Color::W][Piece::R][Castle::SHORT]['sq']['current'] = $wSq;
                    $this->rule[Color::B][Piece::R][Castle::SHORT]['sq']['current'] = $bSq;
                }
            } elseif ($val === Piece::K) {
                $this->rule[Color::W][Piece::K][Castle::LONG]['sq']['current'] = $wSq;
                $this->rule[Color::B][Piece::K][Castle::LONG]['sq']['current'] = $bSq;
                $this->rule[Color::W][Piece::K][Castle::SHORT]['sq']['current'] = $wSq;
                $this->rule[Color::B][Piece::K][Castle::SHORT]['sq']['current'] = $bSq;
            }
        }

        return $this;
    }

    protected function sqs()
    {
        $kPath = $this->path(
            $this->rule[Color::W][Piece::K][Castle::SHORT]['sq']['current'],
            $this->rule[Color::W][Piece::K][Castle::SHORT]['sq']['next']
        );

        $rPath = $this->path(
            $this->rule[Color::W][Piece::R][Castle::SHORT]['sq']['current'],
            $this->rule[Color::W][Piece::R][Castle::SHORT]['sq']['next']
        );

        $path = array_unique(array_merge($kPath, $rPath));

        $this->rule[Color::W][Piece::K][Castle::SHORT]['attack'] = $kPath;
        $this->rule[Color::W][Piece::K][Castle::SHORT]['free'] = array_diff($path, [
            $this->rule[Color::W][Piece::K][Castle::SHORT]['sq']['current'],
            $this->rule[Color::W][Piece::R][Castle::SHORT]['sq']['current'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::W][Piece::K][Castle::LONG]['sq']['current'],
            $this->rule[Color::W][Piece::K][Castle::LONG]['sq']['next']
        );

        $rPath = $this->path(
            $this->rule[Color::W][Piece::R][Castle::LONG]['sq']['current'],
            $this->rule[Color::W][Piece::R][Castle::LONG]['sq']['next']
        );

        $path = array_unique(array_merge($kPath, $rPath));

        $this->rule[Color::W][Piece::K][Castle::LONG]['attack'] = $kPath;
        $this->rule[Color::W][Piece::K][Castle::LONG]['free'] = array_diff($path, [
            $this->rule[Color::W][Piece::K][Castle::LONG]['sq']['current'],
            $this->rule[Color::W][Piece::R][Castle::LONG]['sq']['current'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::B][Piece::K][Castle::SHORT]['sq']['current'],
            $this->rule[Color::B][Piece::K][Castle::SHORT]['sq']['next']
        );

        $rPath = $this->path(
            $this->rule[Color::B][Piece::R][Castle::SHORT]['sq']['current'],
            $this->rule[Color::B][Piece::R][Castle::SHORT]['sq']['next']
        );

        $path = array_unique(array_merge($kPath, $rPath));

        $this->rule[Color::B][Piece::K][Castle::SHORT]['attack'] = $kPath;
        $this->rule[Color::B][Piece::K][Castle::SHORT]['free'] = array_diff($path, [
            $this->rule[Color::B][Piece::K][Castle::SHORT]['sq']['current'],
            $this->rule[Color::B][Piece::R][Castle::SHORT]['sq']['current'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::B][Piece::K][Castle::LONG]['sq']['current'],
            $this->rule[Color::B][Piece::K][Castle::LONG]['sq']['next']
        );

        $rPath = $this->path(
            $this->rule[Color::B][Piece::R][Castle::LONG]['sq']['current'],
            $this->rule[Color::B][Piece::R][Castle::LONG]['sq']['next']
        );

        $path = array_unique(array_merge($kPath, $rPath));

        $this->rule[Color::B][Piece::K][Castle::LONG]['attack'] = $kPath;
        $this->rule[Color::B][Piece::K][Castle::LONG]['free'] = array_diff($path, [
            $this->rule[Color::B][Piece::K][Castle::LONG]['sq']['current'],
            $this->rule[Color::B][Piece::R][Castle::LONG]['sq']['current'],
        ]);

        sort($this->rule[Color::W][Piece::K][Castle::SHORT]['free']);
        sort($this->rule[Color::W][Piece::K][Castle::LONG]['free']);
        sort($this->rule[Color::B][Piece::K][Castle::SHORT]['free']);
        sort($this->rule[Color::B][Piece::K][Castle::LONG]['free']);
    }

    protected function path(string $from, string $to)
    {
        $path = [];
        $i = ord($from[0]);
        $j = ord($to[0]);
        $min = min($i, $j);
        $max = max($i, $j);
        for ($min; $min <= $max; $min++) {
            $file = chr($min);
            $path[] = $file . $from[1];
        }

        return $path;
    }
}
