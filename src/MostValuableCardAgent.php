<?php
/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 03/06/2018
 * Time: 19:17
 */

namespace BriscolaCLI;

class MostValuableCardAgent extends Player
{
    /**
     * @param      $playedCards
     * @param Card $currentWinningCard
     * @param Card $currentTrumpCard
     * @return int
     */
    public function getAction($playedCards, $currentWinningCard, Card $currentTrumpCard)
    {
        $cardPicked = 0;
        /** @var Card $bestCard */
        $bestCard = null;
        $cardsValues = [];
        $canWin = false;
        foreach ($this->getCardsInHand() as $index => $card) {
            /** @var Card $card */
            $currentCardValue = $card->getPointsValue();
            $cardsValues[] = $currentCardValue;
            if($currentWinningCard != null){
                if($card->getSuit() == $currentWinningCard->getSuit() && $currentCardValue > $currentWinningCard->getPointsValue()){
                    $canWin = true;
                    if($bestCard != null){
                        if($bestCard->getSuit() != $card->getSuit()){
                            $bestCard = $card;
                            $cardPicked = $index;
                        }
                        elseif($card->getPointsValue() > $bestCard->getPointsValue()) {
                            $bestCard = $card;
                            $cardPicked = $index;
                        }
                    }
                    else{
                        $bestCard = $card;
                        $cardPicked = $index;
                    }
                }
                elseif($card->getSuit() == $currentTrumpCard->getSuit()){
                    if($canWin && $bestCard->getSuit() == $currentWinningCard->getSuit())
                        continue;

                    if($currentWinningCard->getSuit() == $currentTrumpCard->getSuit()){
                        if($card->getPointsValue() > $currentWinningCard->getPointsValue()){
                            $canWin = true;
                            $bestCard = $card;
                            $cardPicked = $index;
                        }
                    }
                    else{
                        if(!$canWin || ($bestCard != null && $card->getPointsValue() > $bestCard->getPointsValue())){
                            $canWin = true;
                            $bestCard = $card;
                            $cardPicked = $index;
                        }
                    }

                }
            }
            else{
                if($bestCard == null || $bestCard->getPointsValue() < $card->getPointsValue()){
                    $bestCard = $card;
                    $cardPicked = $index;
                }
            }
        }



        return $cardPicked;
    }

}