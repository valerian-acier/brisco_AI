<?php

namespace BriscolaCLI;

use function array_map;
use function array_rand;
use function array_reduce;
use function end;
use function is_null;

/**
 * Class Game
 * @package BriscolaCLI
 */
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

    /**
     * Game constructor.
     * @param array $players
     * @param Deck $deck
     */
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

    /**
     * @return mixed
     */
    public function getWinner()
    {
        $scores = $this->getPlayerScores();

        return reset($scores)['player'];
    }

    /**
     * @return mixed
     */
    public function getLoser()
    {
        $scores = $this->getPlayerScores();

        return end($scores)['player'];
    }

    public function play(CommandLine $commandLine)
    {
        $play = $commandLine->getLine('$prompt');
    }

    /**
     *
     */
    private function pickRandomFirstPlayer()
    {
        $this->nextPlayerToAct = $this->players[array_rand($this->players)];
    }

    /**
     *
     */
    private function drawTrumpCard()
    {
        $this->trumpCard = $this->deck->draw();
    }

    /**
     * @return mixed
     */
    public function isRunning()
    {
        return array_reduce($this->players, function ($carry, $player) {
            /** @var Player $player */
            return $carry && $player->hasCardsLeft();
        }, true);
    }

    /**
     * @return Card
     */
    public function getTrumpCard()
    {
        return $this->trumpCard;
    }

    /**
     *
     */
    public function deal()
    {
        /** @var Player $player */
        foreach ($this->players as $player) {
            while (count($player->getCardsInHand()) < 3 && $this->deck->getCardsLeftCount() > 0) {
                $player->addCardInHand($this->deck->draw());
            }
        }
    }

    /**
     * @return Player
     */
    public function getNextPlayerToAct()
    {
        return $this->nextPlayerToAct;
    }

    /**
     * @return array
     */
    private function getPlayerScores(): array
    {
        $scores = array_map(function ($player) {
            /** @var Player $player */
            return ['player' => $player, 'score' => $player->getScore()];
        }, $this->players);

        uasort($scores, function ($a, $b) {
            return $b['score'] > $a['score'];
        });

        return $scores;
    }
}