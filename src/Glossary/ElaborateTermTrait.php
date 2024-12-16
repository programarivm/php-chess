<?php

namespace Chess\Glossary;

trait ElaborateTermTrait
{
    public array $toElaborate = [];
    
    public array $elaboration = [];

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
