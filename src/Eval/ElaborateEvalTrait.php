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

    protected function shorten(string $intro, bool $ucfirst): array
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
