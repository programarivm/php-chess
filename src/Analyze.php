<?php
namespace PGNChess;

use PGNChess\Board;
use PGNChess\Exception\CastlingException;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;

/**
 * Analyze class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Analyze
{
    /**
     * Validates a board's castling object.
     *
     * @param Board $board
     * @return boolean
     * @throws CastlingException
     */
    public static function castling(Board $board): bool
    {
        $castlingArr = (array)$board->getCastling();

        if (!empty($castlingArr)) {
            !isset($castlingArr[Symbol::WHITE]) ?: $wCastlingArr = (array)$castlingArr[Symbol::WHITE];
            !isset($castlingArr[Symbol::BLACK]) ?: $bCastlingArr = (array)$castlingArr[Symbol::BLACK];
        }

        // check castling object

        if (empty($castlingArr)) {
            throw new CastlingException("The castling object is empty.");
        }

        if (count($castlingArr) !== 2) {
            throw new CastlingException("The castling object must have two properties.");
        }

        // check white's castling object

        if (empty($wCastlingArr)) {
            throw new CastlingException("White's castling object is not set.");
        }

        if (count($wCastlingArr) !== 3) {
            throw new CastlingException("White's castling object must have three properties.");
        }

        if (!isset($wCastlingArr['castled'])) {
            throw new CastlingException("The castled property is not set.");
        }

        if (!isset($wCastlingArr[Symbol::CASTLING_SHORT])) {
            throw new CastlingException("White's castling short property is not set.");
        }

        if (!isset($wCastlingArr[Symbol::CASTLING_LONG])) {
            throw new CastlingException("White's castling long property is not set.");
        }

        // check black's castling object

        if (empty($bCastlingArr)) {
            throw new CastlingException("Black's castling object is not set.");
        }

        if (count($bCastlingArr) !== 3) {
            throw new CastlingException("Black's castling object must have three properties.");
        }

        if (!isset($bCastlingArr['castled'])) {
            throw new CastlingException("Black's castled property is not set.");
        }

        if (!isset($bCastlingArr[Symbol::CASTLING_SHORT])) {
            throw new CastlingException("Black's castling short property is not set.");
        }

        if (!isset($bCastlingArr[Symbol::CASTLING_LONG])) {
            throw new CastlingException("Black's castling long property is not set.");
        }

        // check castling object's info

        $wKing = $board->getPieceByPosition('e1');
        $wRookA1 = $board->getPieceByPosition('a1');
        $wRookH1 = $board->getPieceByPosition('h1');

        $bKing = $board->getPieceByPosition('e8');
        $bRookA8 = $board->getPieceByPosition('a8');
        $bRookH8 = $board->getPieceByPosition('h8');

        // check white's castling info

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_LONG} === true &&
            !(isset($wKing) && $wKing->getIdentity() === Symbol::KING && $wKing->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($wKing) && $wKing->getIdentity() === Symbol::KING && $wKing->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_LONG} === true &&
            !(isset($wRookA1) && $wRookA1->getIdentity() === Symbol::ROOK && $wRookA1->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's a1 rook was already moved.");
        }

        if ($board->getCastling()->{Symbol::WHITE}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($wRookH1) && $wRookH1->getIdentity() === Symbol::ROOK && $wRookH1->getColor() === Symbol::WHITE)) {
                throw new CastlingException("White's h1 rook was already moved.");
        }

        // check black's castling info

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_LONG} === true &&
            !(isset($bKing) && $bKing->getIdentity() === Symbol::KING && $bKing->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($bKing) && $bKing->getIdentity() === Symbol::KING && $bKing->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's king was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_LONG} === true &&
            !(isset($bRookA8) && $bRookA8->getIdentity() === Symbol::ROOK && $bRookA8->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's a8 rook was already moved.");
        }

        if ($board->getCastling()->{Symbol::BLACK}->{Symbol::CASTLING_SHORT} === true &&
            !(isset($bRookH8) && $bRookH8->getIdentity() === Symbol::ROOK && $bRookH8->getColor() === Symbol::BLACK)) {
                throw new CastlingException("Black's h8 rook was already moved.");
        }

        return true;
    }

}
