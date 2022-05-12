<?php

namespace Chess\Media;

use Chess\Board;

class BoardToMp4
{
    protected Board $board;

    protected bool $flip;

    public function __construct(Board $board, bool $flip = false)
    {
        $this->board = $board;

        $this->flip = $flip;
    }

    public function output(string $filepath): string
    {
        if (!file_exists($filepath)) {
            throw new \InvalidArgumentException('The folder does not exist.');
        }

        $filename = uniqid();

        $this->frames($filepath, $filename)
            ->animate(escapeshellarg($filepath), $filename)
            ->cleanup($filepath, $filename);

        return $filename.'.mp4';
    }

    private function frames(string $filepath, string $filename): BoardToMp4
    {
        $board = new Board();
        $boardToPng = new BoardToPng($board, $this->flip);
        foreach ($this->board->getHistory() as $key => $item) {
            $n = sprintf("%02d", $key);
            $board->play($item->move->color, $item->move->pgn);
            $boardToPng->setBoard($board)->output($filepath, "{$filename}_{$n}");
        }

        return $this;
    }

    private function animate(string $filepath, string $filename): BoardToMp4
    {
        $cmd = "ffmpeg -r 1 -pattern_type glob -i {$filepath}/{$filename}*.png -vf fps=25 -x264-params threads=6 -pix_fmt yuv420p {$filepath}/{$filename}.mp4";
        $escapedCmd = escapeshellcmd($cmd);
        exec($escapedCmd);

        return $this;
    }

    private function cleanup(string $filepath, string $filename): void
    {
        if (file_exists("{$filepath}/$filename.mp4")) {
            array_map('unlink', glob($filepath . '/*.png'));
        }
    }
}
