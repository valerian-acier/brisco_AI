<?php
/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 03/06/2018
 * Time: 19:23
 */

namespace BriscolaCLI;

class RandomCardAgent extends Player
{

    /**
     * @param $playedCards
     * @param $currentWinningCard
     * @param $currentTrumpCard
     * @return int
     */
    public function getAction($playedCards, $currentWinningCard,Card $currentTrumpCard)
    {
        return rand(0, count($this->getCardsInHand())-1);
    }

}