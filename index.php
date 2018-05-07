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
$game = new Game([$player1, $player2], $deck, new CommandLine());
$game->start();

$commandLine = new CommandLine();

$commandLine->sayLine("================== GAME START ===============");

while ($game->isRunning()) {
    $game->deal();
    $game->play();
}

$commandLine->sayLine("================== GAME END ===============");

$winner = $game->getWinner();
$loser = $game->getLoser();

if ($winner->getScore() == $loser->getScore()) {
    $commandLine->sayLine("DRAW !");
}
else {
    $commandLine->sayLine("Winner is {$winner->getName()} with {$winner->getScore()} points !");
    $commandLine->sayLine("Loser is {$loser->getName()} with {$loser->getScore()} points !");
}