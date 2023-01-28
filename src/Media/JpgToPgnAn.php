<?php

namespace Chess\Media;

use Chess\Variant\Classical\PGN\AN\Piece;
use Rubix\ML\PersistentModel;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;

class JpgToPgnAn
{
    const ML_PATH = __DIR__ . '/../../ml';

    protected string $filename;

    protected \GdImage $image;

    protected array $pieces;

    protected array $report;

    protected string $prediction;

    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->image = imagecreatefromjpeg($filename);

        $this->pieces = [
            Piece::B,
            Piece::K,
            Piece::N,
            Piece::P,
            Piece::Q,
            Piece::R,
            mb_strtolower(Piece::B),
            mb_strtolower(Piece::K),
            mb_strtolower(Piece::N),
            mb_strtolower(Piece::P),
            mb_strtolower(Piece::Q),
            mb_strtolower(Piece::R),
        ];

        $this->calcReport();
    }

    protected function calcReport()
    {
        foreach ($this->pieces as $piece) {
            $samples[] = [$this->image];
            $labels[] = $piece;
        }

        $dataset = new Labeled($samples, $labels);
        $estimator = PersistentModel::load(
            new Filesystem(self::ML_PATH.'/piece.rbx')
        );

        $predictions = $estimator->predict($dataset);

        $results = (new MulticlassBreakdown())
            ->generate($predictions, $dataset->labels());

        $this->report = $results['classes'];
    }

    public function predict()
    {
        foreach ($this->report as $key => $val) {
            if ($val['true positives'] === 1) {
                return $key;
            }
        }

        return null;
    }
}
