<?php

namespace Chess\Media\PGN\AN;

use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem;

class JpgToPiece
{
    const ML_PATH = __DIR__ . '/../../../../ml';

    protected string $filename;

    protected \GdImage $image;

    protected PersistentModel $estimator;

    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->image = imagecreatefromjpeg($filename);

        $this->estimator = PersistentModel::load(
            new Filesystem(self::ML_PATH.'/piece.rbx')
        );
    }

    public function predict(): string
    {
        $dataset = new Unlabeled([[$this->image]]);
        $prediction = $this->estimator->predict($dataset);

        return $prediction[0];
    }
}
