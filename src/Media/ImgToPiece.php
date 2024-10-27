<?php

namespace Chess\Media;

use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem;

class ImgToPiece
{
    const ML_PATH = __DIR__ . '/../../ml';

    protected \GdImage $image;

    protected PersistentModel $estimator;

    public function __construct(\GdImage $image)
    {
        $this->image = $image;

        $this->estimator = PersistentModel::load(
            new Filesystem(self::ML_PATH . '/piece.rbx')
        );
    }

    public function predict(): string
    {
        $dataset = new Unlabeled([[$this->image]]);
        $prediction = $this->estimator->predict($dataset);

        return $prediction[0];
    }
}
