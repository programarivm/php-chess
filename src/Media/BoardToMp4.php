<?php

namespace Chess\Media;

use Chess\Exception\MediaException;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;

class BoardToMp4
{
    const MAX_MOVES = 300;

    protected $ext = '.mp4';

    protected SanMovetext $sanMovetext;

    protected AbstractBoard $board;

    protected bool $flip;

    public function __construct(string $movetext, AbstractBoard $board, bool $flip = false)
    {
        $this->sanMovetext = new SanMovetext($board->move, $movetext);
        if (!$this->sanMovetext->validate()) {
            throw new MediaException();
        }
        if (self::MAX_MOVES < count($this->sanMovetext->moves)) {
            throw new MediaException();
        }
        $this->board = $board;
        $this->flip = $flip;
    }

    public function output(string $filepath, string $filename = ''): string
    {
        if (!file_exists($filepath)) {
            throw new \InvalidArgumentException('The folder does not exist.');
        }

        $filename
            ? $filename = $filename . $this->ext
            : $filename = uniqid() . $this->ext;

        $this->frames($filepath, $filename)
            ->animate(escapeshellarg($filepath), $filename)
            ->cleanup($filepath, $filename);

        return $filename;
    }

    private function frames(string $filepath, string $filename): BoardToMp4
    {
        $boardToPng = new BoardToPng($this->board, $this->flip);
        $boardToPng->output($filepath, "{$filename}_000");
        foreach ($this->sanMovetext->moves as $key => $val) {
            $n = sprintf("%03d", $key + 1);
            $this->board->play($this->board->turn, $val);
            $boardToPng->setBoard($this->board)->output($filepath, "{$filename}_{$n}");
        }

        return $this;
    }

    private function animate(string $filepath, string $filename): BoardToMp4
    {
        $cmd = "ffmpeg -r 1 -pattern_type glob -i {$filepath}/{$filename}*.png -vf fps=25 -x264-params threads=6 -pix_fmt yuv420p {$filepath}/{$filename}";
        $escapedCmd = escapeshellcmd($cmd);
        exec($escapedCmd);

        return $this;
    }

    private function cleanup(string $filepath, string $filename): void
    {
        if (file_exists("{$filepath}/$filename")) {
            array_map('unlink', glob($filepath . '/*.png'));
        }
    }
}
