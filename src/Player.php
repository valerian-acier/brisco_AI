<?php

namespace BriscolaCLI;

use function array_reduce;
use function array_values;
use function var_dump;

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
        foreach ($cardsWon as $cardWon) {
            $this->cardsWon[] = $cardWon['card'];
        }
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

    public function removeCardFromHand($cardPicked)
    {
        unset($this->cardsInHand[$cardPicked]);
        $this->cardsInHand = array_values($this->cardsInHand);
    }

    public function displayCards()
    {
        $string = "";

        foreach ($this->getCardsInHand() as $index => $card) {
            $string .= "($index)" . $card;

            if ($index < count($this->getCardsInHand())) {
                $string .= " ";
            }
        }

        return $string;
    }
}