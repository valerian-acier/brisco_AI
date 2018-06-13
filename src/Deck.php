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
    private $totalNumberOfCards = 0;
    private $arrayRandomizer = 0;
    private $cardsAfterShuffle = [];

    /**
     * Deck constructor.
     * @param ArrayRandomizer $arrayRandomizer
     */
    public function __construct(ArrayRandomizer $arrayRandomizer)
    {
        $this->arrayRandomizer = $arrayRandomizer;
        $this->reset();
    }


    public function reset(){
        if(count($this->cardsAfterShuffle) != 0) {
            $this->cards = $this->cardsAfterShuffle;
            return;
        }

        foreach (Card::getSuits() as $suit) {
            foreach (Card::getRanks() as $rank) {
                $this->cards[] = new Card($suit, $rank);
                $this->totalNumberOfCards++;
            }
        }

        $this->cards = $this->arrayRandomizer->randomize($this->cards);
        $this->cardsAfterShuffle = $this->cards;
    }

    /**
     * @return int
     */
    public function getCardsLeftCount()
    {
        return count($this->cards);
    }

    /**
     * @return int
     */
    public function getTotalNumberOfCards(){
        return $this->totalNumberOfCards;
    }

    /**
     * @param int $cardsCount
     * @return array|mixed
     */
    public function draw($cardsCount = 1)
    {

        $commandLine = new NoDisplayCommandLine();
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