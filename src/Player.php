<?php

namespace BriscolaCLI;

use function array_reduce;

class Player
{
    /**
     * @var array
     */
    private $cardsInHand = [];
    /**
     * @var array
     */
    private $cardsWon = [];
    /**
     * @var string
     */
    private $name = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

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

    public function addCardsWon(array $cardsWon)
    {
        $this->cardsWon = $this->cardsWon + $cardsWon;
    }

    public function getScore()
    {
        return array_reduce($this->cardsWon, function ($carry, Card $card) {
            return $carry + $card->getPointsValue();
        }, 0);
    }

    public function getName()
    {
        return $this->name;
    }
}