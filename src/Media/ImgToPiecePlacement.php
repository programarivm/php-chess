<?php

namespace Chess\Media;

use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Persisters\Filesystem;

class ImgToPiecePlacement
{
    const ML_PATH = __DIR__ . '/../../ml';

    private \GdImage $image;

    private PersistentModel $estimator;

    private array $tiles;

    public function __construct(\GdImage $image)
    {
        $this->image = $image;

        $this->estimator = PersistentModel::load(
            new Filesystem(self::ML_PATH . '/piece.rbx')
        );
    }

    public function predict(): string
    {
        $side  = imagesx($this->image) / 8;
        $y = 0;
        for ($i = 0; $i < 8; $i++) {
            $x = 0;
            for ($j = 0; $j < 8; $j++) {
                $imagecrop = imagecrop($this->image, [
                    'x' => $x,
                    'y' => $y,
                    'width' => $side,
                    'height' => $side,
                ]);
                $this->tiles[] = [$imagecrop];
                $x += $side;
            }
            $y += $side;
        }

        $dataset = new Unlabeled($this->tiles);
        $prediction = $this->estimator->predict($dataset);

        $result = implode('', $prediction);
        $result = chunk_split($result, 8, '/');
        $result = substr($result, 0, -1);

        return str_replace(
            ['11111111', '1111111', '111111', '11111', '1111', '111', '11'],
            ['8', '7', '6', '5', '4', '3', '2'],
            $result
        );
    }
}
