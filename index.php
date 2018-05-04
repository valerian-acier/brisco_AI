<?php

use BriscolaCLI\ArrayRandomizer;
use BriscolaCLI\CommandLine;
use BriscolaCLI\Deck;
use BriscolaCLI\Game;
use BriscolaCLI\Player;

require 'vendor/autoload.php';

$player1 = new Player('Johnny');
$player2 = new Player('Mary');
$deck = new Deck(new ArrayRandomizer());
$game = new Game([$player1, $player2], $deck);

while ($game->isRunning()) {
    $game->deal();
    $game->play(new CommandLine());
}

$winner = $game->getWinner();
$loser = $game->getLoser();

var_dump($winner);
var_dump($loser);