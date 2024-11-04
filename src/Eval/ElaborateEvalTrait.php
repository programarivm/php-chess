<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;

trait ElaborateEvalTrait
{
    protected array $elaboration = [];

    public function getElaboration(): array
    {
        return $this->elaboration;
    }

    protected function shorten(array $result, string $singular, string $plural): void
    {
        $sqs = [...$result[Color::W], ...$result[Color::B]];

        if (count($sqs) > 1) {
            $str = '';
            $keys = array_keys($sqs);
            $lastKey = end($keys);
            foreach ($sqs as $key => $val) {
                if ($key === $lastKey) {
                    $str = substr($str, 0, -2);
                    $str .= " and $val are {$plural}.";
                } else {
                    $str .= "$val, ";
                }
            }
            $this->elaboration = [
                $str,
            ];
        } elseif (count($sqs) === 1) {
            $this->elaboration = [
                "$sqs[0] is {$singular}.",
            ];
        }
    }

    protected function reelaborate(string $intro, bool $ucfirst): array
    {
        if ($this->elaboration) {
            $rephrase = '';

            foreach ($this->elaboration as $val) {
                $rephrase .= $val . ', ';
            }

            if ($ucfirst) {
                $rephrase = ucfirst($rephrase);
            }

            $this->elaboration = [
                $intro . substr_replace($rephrase, '.', -2),
            ];
        }

        return $this->elaboration;
    }
}
