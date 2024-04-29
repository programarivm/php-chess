<?php

namespace Chess\Computer;

use Chess\Heuristics\FenHeuristics;
use Chess\ML\Supervised\Classification\CountLabeller;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * GuessComputer
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class GuessComputer extends AbstractComputer
{
    /**
     * Returns a chess move.
     *
     * @param \Chess\Variant\Classical\Board $board
     * @return null|object
     */
    public function move(Board $board): ?object
    {
        $moves = [];

        foreach ($board->getPieces($board->getTurn()) as $piece) {
            foreach ($piece->sqs() as $sq) {
                $lan = "{$piece->getSq()}{$sq}";
                $clone = unserialize(serialize($board));
                if ($clone->playLan($clone->getTurn(), $lan)) {
                    $last = array_slice($clone->getHistory(), -1)[0];
                    $balance = (new FenHeuristics($clone))->getBalance();
                    $label = (new CountLabeller())->label($balance);
                    $moves[] = [
                        'diff' => $label[Color::W] - $label[Color::B],
                        'pgn' => $last->move->pgn,
                        'lan' => $lan,
                    ];
                }
            }
        }

        $key = array_column($moves, 'diff');

        if ($board->getTurn() === Color::W) {
            array_multisort($key, SORT_DESC, $moves);
            foreach ($moves as $key => $val) {
                if ($val['diff'] < $moves[0]['diff']) {
                    unset($moves[$key]);
                }
            }
        } else {
            array_multisort($key, SORT_ASC, $moves);
            foreach ($moves as $key => $val) {
                if ($val['diff'] > $moves[0]['diff']) {
                    unset($moves[$key]);
                }
            }
        }

        shuffle($moves);

        if ($moves) {
            return (object) $moves[0];
        }

        return null;
    }
}
