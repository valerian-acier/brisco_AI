<?php

namespace BriscolaCLI;

class Player
{
    private $cardsInHand = [];

    public function addCardInHand(Card $card)
    {
        $this->cardsInHand[] = $card;
    }

    public function hasCardsLeft()
    {
        return count($this->cardsInHand) > 0;
    }

    public function getCardsInHand()
    {
        return $this->cardsInHand;
    }
}