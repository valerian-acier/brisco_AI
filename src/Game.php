<?php

namespace BriscolaCLI;

use function array_map;
use function array_rand;
use function array_reduce;
use function end;
use function is_null;
use function var_dump;

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
     * @var array
     */
    private $playersPlayed = [];
    /**
     * @var array
     */
    private $cardsPlayed = [];
    /**
     * @var CommandLine
     */
    private $commandLine;
    /**
     * @var bool
     */
    private $trumpCardDrawn = false;

    /**
     * Game constructor.
     * @param array $players
     * @param Deck $deck
     * @param CommandLine $commandLine
     */
    public function __construct(array $players, Deck $deck, CommandLine $commandLine)
    {
        $this->players = $players;
        $this->deck = $deck;
        $this->commandLine = $commandLine;
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

    public function play()
    {
        while ($this->onePlayerCanPlay()) {
            $this->commandLine->sayLine("Current trump : {$this->trumpCard}");
            $nextPlayer = $this->getNextPlayerToAct();
            $cardPicked = $this->commandLine->getLine(
                "{$nextPlayer->getName()} turn. Cards : {$nextPlayer->displayCards()}"
            );

            if ($cardPicked == "") {
                $cardPicked = 0;
            }

            $this->addCardPlayed($nextPlayer, $cardPicked);
            $this->playerPlayed($nextPlayer);
        }

        $this->resetPlayersPlayed();
        $winner = $this->getCurrentHandWinner();
        $winner->addCardsWon($this->getCardsPlayed());
        $this->commandLine->sayLine(
            "Hand finished. Winner is {$winner->getName()}"
        );
        $this->resetCardsPlayed();
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
            while (count($player->getCardsInHand()) < 3) {
                $cardDrew = $this->deck->draw();

                if (!$cardDrew && !$this->trumpCardDrawn) {
                    $this->commandLine->sayLine("No more card to draw. Take the trump card : {$this->trumpCard}");
                    $cardDrew = $this->trumpCard;
                    $this->trumpCardDrawn = true;
                }
                else if (!$cardDrew && $this->trumpCardDrawn) {
                    break;
                }

                $player->addCardInHand($cardDrew);
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

    private function onePlayerCanPlay()
    {
        return count($this->playersPlayed) < count($this->players);
    }

    private function addCardPlayed(Player $nextPlayer, $cardPicked)
    {
        $cardPlayed = $nextPlayer->getCardsInHand()[$cardPicked];
        $this->cardsPlayed[] = ['player' => $nextPlayer, 'card' => $cardPlayed];
        $nextPlayer->removeCardFromHand($cardPicked);

        $this->commandLine->sayLine("Player {$nextPlayer->getName()} played card {$cardPlayed}.");
    }

    private function playerPlayed(Player $nextPlayer)
    {
        $this->playersPlayed[] = $nextPlayer;

        if ($this->players[0]->getName() == $nextPlayer->getName()) {
            $this->nextPlayerToAct = $this->players[1];
        }

        if ($this->players[1]->getName() == $nextPlayer->getName()) {
            $this->nextPlayerToAct = $this->players[0];
        }
    }

    private function getPlayersName()
    {
        return array_map(function ($player) {
            return $player->getName();
        }, $this->players);
    }

    private function resetPlayersPlayed()
    {
        $this->playersPlayed = [];
    }

    private function getCurrentHandWinner()
    {
        $cardsWinner = new CardsWinner();
        $winningCard = $cardsWinner->getWinningCard(array_map(function ($cardPlayed) {
            return $cardPlayed['card'];
        }, $this->cardsPlayed), $this->getTrumpCard());

        foreach ($this->cardsPlayed as $cardPlayed) {
            if ($cardPlayed['card'] == $winningCard) {
                return $cardPlayed['player'];
            }
        }
    }

    private function getCardsPlayed()
    {
        return $this->cardsPlayed;
    }

    private function resetCardsPlayed()
    {
        $this->cardsPlayed = [];
    }
}