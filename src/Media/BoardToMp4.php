<?php

namespace Chess\Media;

use Chess\Movetext;
use Chess\Exception\MediaException;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Capablanca\FEN\StrToBoard as CapablancaFenStrToBoard;
use Chess\Variant\Capablanca\PGN\Move as CapablancaPgnMove;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Chess960\FEN\StrToBoard as Chess960FenStrToBoard;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FEN\StrToBoard as ClassicalFenStrToBoard;
use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;

class BoardToMp4
{
    const MAX_MOVES = 300;

    protected string $variant;

    protected Movetext $movetext;

    protected string $fen;

    protected array $startPos;

    protected bool $flip;

    protected ClassicalBoard $board;

    protected $ext = '.mp4';

    public function __construct(
        string $variant,
        string $movetext,
        string $fen = '',
        string $startPos = '',
        bool $flip = false
    ) {
        $this->variant = $variant;
        $this->fen = $fen;
        $this->startPos = str_split($startPos);
        $this->flip = $flip;

        if ($variant === Chess960Board::VARIANT) {
            $move = new ClassicalPgnMove();
        } elseif ($variant === CapablancaBoard::VARIANT) {
            $move = new CapablancaPgnMove();
        } elseif ($variant === ClassicalBoard::VARIANT) {
            $move = new ClassicalPgnMove();
        } else {
            throw new MediaException();
        }

        $this->movetext = new Movetext($move, $movetext);
        if (!$this->movetext->validate()) {
            throw new MediaException();
        }
        if (self::MAX_MOVES < count($this->movetext->getMoves())) {
            throw new MediaException();
        }

        if ($this->fen) {
            if ($this->variant === Chess960Board::VARIANT) {
                $this->board = (new Chess960FenStrToBoard($this->fen, $this->startPos))
                    ->create();
            } elseif ($this->variant === CapablancaBoard::VARIANT) {
                $this->board = (new CapablancaFenStrToBoard($this->fen))
                    ->create();
            } elseif ($this->variant === ClassicalBoard::VARIANT) {
                $this->board = (new ClassicalFenStrToBoard($this->fen))
                    ->create();
            }
        } else {
            if ($this->variant === Chess960Board::VARIANT) {
                $this->board = new Chess960Board($this->startPos);
            } elseif ($this->variant === CapablancaBoard::VARIANT) {
                $this->board = new CapablancaBoard();
            } elseif ($this->variant === ClassicalBoard::VARIANT) {
                $this->board = new ClassicalBoard();
            }
        }
    }

    public function output(string $filepath, string $filename = ''): string
    {
        if (!file_exists($filepath)) {
            throw new \InvalidArgumentException('The folder does not exist.');
        }

        $filename ? $filename = $filename.$this->ext : $filename = uniqid().$this->ext;

        $this->frames($filepath, $filename)
            ->animate(escapeshellarg($filepath), $filename)
            ->cleanup($filepath, $filename);

        return $filename;
    }

    private function frames(string $filepath, string $filename): BoardToMp4
    {
        $boardToPng = new BoardToPng($this->board, $this->flip);
        $boardToPng->output($filepath, "{$filename}_000");
        foreach ($this->movetext->getMoves() as $key => $val) {
            $n = sprintf("%03d", $key + 1);
            $this->board->play($this->board->getTurn(), $val);
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
