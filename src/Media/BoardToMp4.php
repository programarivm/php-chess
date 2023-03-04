<?php

namespace Chess\Media;

use Chess\Game;
use Chess\Movetext;
use Chess\Exception\MediaException;
use Chess\Variant\Capablanca80\Board as Capablanca80Board;
use Chess\Variant\Capablanca80\FEN\StrToBoard as Capablanca80FenStrToBoard;
use Chess\Variant\Capablanca80\PGN\Move as Capablanca80PgnMove;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;
use Chess\Variant\Classical\Board as ClassicalBoard;

class BoardToMp4
{
    const MAX_MOVES = 300;

    protected string $variant;

    protected Movetext $movetext;

    protected string $fen;

    protected string $startPos;

    protected bool $flip;

    protected ClassicalBoard $board;

    public function __construct(
        string $variant,
        string $movetext,
        string $fen = '',
        string $startPos = '',
        bool $flip = false
    ) {
        $this->variant = $variant;
        $this->fen = $fen;
        $this->startPos = $startPos;
        $this->flip = $flip;

        if ($variant === Game::VARIANT_960) {
            $move = new ClassicalPgnMove();
        } elseif ($variant === Game::VARIANT_CAPABLANCA_80) {
            $move = new Capablanca80PgnMove();
        } elseif ($variant === Game::VARIANT_CLASSICAL) {
            $move = new ClassicalPgnMove();
        } else {
            throw new MediaException();
        }

        $this->movetext = new Movetext($move, $movetext);
        if (!$this->movetext->validate()) {
            throw new MediaException();
        }
        if (self::MAX_MOVES < count($this->movetext->getMovetext()->moves)) {
            throw new MediaException();
        }

        if ($this->fen) {
            if ($this->variant === Game::VARIANT_960) {
                $this->board = (new ClassicalFenStrToBoard($this->fen))->create();
            } elseif ($this->variant === Game::VARIANT_CAPABLANCA_80) {
                $this->board = (new Capablanca80FenStrToBoard($this->fen))->create();
            } elseif ($this->variant === Game::VARIANT_CLASSICAL) {
                $this->board = (new ClassicalFenStrToBoard($this->fen))->create();
            }
        } else {
            if ($this->variant === Game::VARIANT_960) {
                $this->board = new Chess960Board($this->board->getStartPos());
            } elseif ($this->variant === Game::VARIANT_CAPABLANCA_80) {
                $this->board = new Capablanca80Board();
            } elseif ($this->variant === Game::VARIANT_CLASSICAL) {
                $this->board = new ClassicalBoard();
            }
        }
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
        $boardToPng = new BoardToPng($this->board, $this->flip);
        $boardToPng->output($filepath, "{$filename}_000");
        foreach ($this->movetext->getMovetext()->moves as $key => $val) {
            $n = sprintf("%03d", $key + 1);
            $this->board->play($this->board->getTurn(), $val);
            $boardToPng->setBoard($this->board)->output($filepath, "{$filename}_{$n}");
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
