<?php

namespace BriscolaCLI;

use function array_map;
use function array_shift;

/**
 * Class Deck
 * @package BriscolaCLI
 */
class Deck
{
    /**
     * @var array
     */
    private $cards = [];

    /**
     * Deck constructor.
     * @param ArrayRandomizer $arrayRandomizer
     */
    public function __construct(ArrayRandomizer $arrayRandomizer)
    {
        foreach (Card::getSuits() as $suit) {
            foreach (Card::getRanks() as $rank) {
                $this->cards[] = new Card($suit, $rank);
            }
        }

        $this->cards = $arrayRandomizer->randomize($this->cards);
    }

    /**
     * @return int
     */
    public function getCardsLeftCount()
    {
        return count($this->cards);
    }

    /**
     * @param int $cardsCount
     * @return array|mixed
     */
    public function draw($cardsCount = 1)
    {
        if ($this->getCardsLeftCount() == 0) {
            return false;
        }

        $commandLine = new CommandLine();
        $commandLine->sayLine("{$this->getCardsLeftCount()} cards left.");


        if ($cardsCount === 1) {
            $card = array_shift($this->cards);
            $commandLine->sayLine("Card drew : $card.");

            return $card;
        }

        $cards = [];

        for ($i = 0; $i < $cardsCount; $i++) {
            $cards[] = array_shift($this->cards);
        }

        return $cards;
    }
}