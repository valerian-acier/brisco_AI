<?php

namespace BriscolaCLI;

use function array_rand;
use function array_reduce;

class Game
{
    /**
     * @var array
     */
    private $players;
    /**
     * @var Deck
     */
    private $deck;
    /**
     * @var Card
     */
    private $trumpCard;
    /**
     * @var Player
     */
    private $nextPlayerToAct;

    public function __construct(array $players, Deck $deck)
    {
        $this->players = $players;
        $this->deck = $deck;
    }

    public function start()
    {
        $this->pickRandomFirstPlayer();
        $this->deal();
        $this->drawTrumpCard();
    }

    private function pickRandomFirstPlayer()
    {
        $this->nextPlayerToAct = $this->players[array_rand($this->players)];
    }

    private function drawTrumpCard()
    {
        $this->trumpCard = $this->deck->draw();
    }

    public function isRunning()
    {
        return array_reduce($this->players, function ($carry, $player) {
            /** @var Player $player */
            return $carry && $player->hasCardsLeft();
        }, true);
    }

    public function getTrumpCard()
    {
        return $this->trumpCard;
    }

    public function deal()
    {
        /** @var Player $player */
        foreach ($this->players as $player) {
            while (count($player->getCardsInHand()) < 3 && $this->deck->getCardsLeftCount() > 0) {
                $player->addCardInHand($this->deck->draw());
            }
        }
    }

    public function getNextPlayerToAct()
    {
        return $this->nextPlayerToAct;
    }


}