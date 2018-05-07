<?php

namespace BriscolaCLI;

class CardsWinner
{
    public function getWinningCard(array $cards, Card $trumpCard)
    {
        /** @var Card $firstCard */
        $firstCard = $cards[0];
        /** @var Card $secondCard */
        $secondCard = $cards[1];

        $firstCardIsTrump = $firstCard->getSuit() == $trumpCard->getSuit();
        $secondCardIsTrump = $secondCard->getSuit() == $trumpCard->getSuit();

        if ($firstCardIsTrump && !$secondCardIsTrump) {
            return $firstCard;
        }

        if (!$firstCardIsTrump && $secondCardIsTrump) {
            return $secondCard;
        }

        if ($firstCard->getSuit() == $secondCard->getSuit()) {
            return $firstCard->getPointsValue() > $secondCard->getPointsValue() ? $firstCard : $secondCard;
        }

        return $firstCard;
    }
}