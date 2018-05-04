<?php

namespace BriscolaCLI;

use function array_reduce;

/**
 * Class Player
 * @package BriscolaCLI
 */
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

    /**
     * Player constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Card $card
     */
    public function addCardInHand(Card $card)
    {
        $this->cardsInHand[] = $card;
    }

    /**
     * @return bool
     */
    public function hasCardsLeft()
    {
        return count($this->cardsInHand) > 0;
    }

    /**
     * @return array
     */
    public function getCardsInHand()
    {
        return $this->cardsInHand;
    }

    /**
     * @param array $cardsWon
     */
    public function addCardsWon(array $cardsWon)
    {
        $this->cardsWon = $this->cardsWon + $cardsWon;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return array_reduce($this->cardsWon, function ($carry, Card $card) {
            return $carry + $card->getPointsValue();
        }, 0);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}