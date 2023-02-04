<?php

namespace Chess\Media\FEN\Field;

use Chess\Media\PGN\AN\JpgToPiece;

class JpgToPiecePlacement
{
    const STORAGE_TMP_FOLDER = __DIR__.'/../../../../storage/tmp';

    protected string $filename;

    protected \GdImage $image;

    protected array $size;

    protected string $uniqid;

    protected array $filepaths;

    protected string $piecePlacement;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->image = imagecreatefromjpeg($filename);
        $this->size = getimagesize($filename);
        $this->uniqid = uniqid();
        $this->filepaths = [];
        $this->piecePlacement = '';

        $this->calcTiles();
    }

    protected function calcTiles(): void
    {
        $side = $this->size[0] / 8;
        $y = 0;
        for ($i = 0; $i < 8; $i++) {
            $x = 0;
            for ($j = 0; $j < 8; $j++) {
                $tile = imagecrop($this->image, [
                    'x' => $x,
                    'y' => $y,
                    'width' => $side,
                    'height' => $side,
                ]);
                if ($tile !== false) {
                    $filepath = self::STORAGE_TMP_FOLDER."/{$this->uniqid}$i$j.jpg";
                    $this->filepaths[$i][] = $filepath;
                    imagejpeg($tile, $filepath);
                    imagedestroy($tile);
                }
                $x += $side;
            }
            $y += $side;
        }
    }

    protected function cleanup(): void
    {
        $files = glob(self::STORAGE_TMP_FOLDER."/{$this->uniqid}*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function predict(): string
    {
        foreach ($this->filepaths as $key => $val) {
            foreach ($val as $filepath) {
                $prediction = (new JpgToPiece($filepath))->predict();
                $prediction === 'empty'
                    ? $this->piecePlacement .= '1'
                    : $this->piecePlacement .= $prediction;
            }
            $this->piecePlacement .= '/';
        }

        $this->cleanup();

        $this->piecePlacement = substr($this->piecePlacement, 0, -1);
        $this->piecePlacement = str_replace('11111111', '8', $this->piecePlacement);
        $this->piecePlacement = str_replace('1111111', '7', $this->piecePlacement);
        $this->piecePlacement = str_replace('111111', '6', $this->piecePlacement);
        $this->piecePlacement = str_replace('11111', '5', $this->piecePlacement);
        $this->piecePlacement = str_replace('1111', '4', $this->piecePlacement);
        $this->piecePlacement = str_replace('111', '3', $this->piecePlacement);
        $this->piecePlacement = str_replace('11', '2', $this->piecePlacement);

        return $this->piecePlacement;
    }
}
