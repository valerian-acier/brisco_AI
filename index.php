<?php

use BriscolaCLI\ArrayNotRandomizer;
use BriscolaCLI\ArrayRandomizer;
use BriscolaCLI\CommandLine;
use BriscolaCLI\Deck;
use BriscolaCLI\Game;
use BriscolaCLI\MostValuableCardAgent;
use BriscolaCLI\NoDisplayCommandLine;
use BriscolaCLI\Player;
use BriscolaCLI\RandomCardAgent;
use BriscolaCLI\NeuralNetCardAgent;

require 'vendor/autoload.php';

/*$player1 = new MostValuableCardAgent('MostValuableAgent');
$player2 = new RandomCardAgent('RandomAgent');
$deck    = new Deck(new ArrayRandomizer());
$game    = new Game([$player1, $player2], $deck, new NoDisplayCommandLine());
$scores  = $game->doXGame(100);
print_r($scores);*/
ini_set('memory_limit', '-1');
geneticNeuralNetImprovement(200, 0.2, 100);

function tournamentSelection($population)
{
    $selection = array_rand($population, 150);
    $r         = [];
    foreach ($selection as $id) {
        $r[] = ['fitness' => $population[$id]['fitness'], 'specimen' => $population[$id]['specimen'], 'id' => $id];
    }

    uasort($r, function ($a, $b) {
        return $b['fitness'] > $a['fitness'];
    });

    foreach ($r as $rr) {
        print($rr["fitness"] . "\n");
    }

    return [array_slice($r, 0, 10), array_slice($r, 10)];
}

function croissement(NeuralNetCardAgent $pere, NeuralNetCardAgent $mere)
{
    $agent = new NeuralNetCardAgent('NeuralNetAgent');
    $agent->crossing($pere, $mere);

    return $agent;
}

function geneticNeuralNetImprovement($specimensCount, $mutationRate, $numberOfGameToEvaluate)
{
    $populations     = [];
    $currentOpponent = new MostValuableCardAgent('MostValuableAgent');
    $randomOpponent  = new RandomCardAgent('RandomAgent');
    for ($i = 0; $i < $specimensCount; $i++) {
        $specimen      = new NeuralNetCardAgent('NeuralNetAgent');
        $populations[] = ['specimen' => $specimen, 'fitness' => -1];
    }

    $decks = [];
    for ($i = 0; $i < $numberOfGameToEvaluate; $i++) {
        $decks[] = new Deck(new ArrayRandomizer());
    }

    $notAltered = [];
    for ($i = 0; $i < 10000; $i++) {
        $best       = -1;
        $bestNeural = null;

        print("Generation " . $i . "\n");
        foreach ($populations as $index => $specimen) {
            $game   = new Game([$specimen['specimen'], $currentOpponent], $decks, new NoDisplayCommandLine(), new ArrayNotRandomizer());
            $scores = $game->doXGame($numberOfGameToEvaluate);
            //$scores2 = $game2->doXGame($numberOfGameToEvaluate*100);
            //$scores['NeuralNetAgent'] += $scores2['NeuralNetAgent'];
            //$scores['MostValuableAgent'] += $scores2['RandomAgent'];
            if ($scores['NeuralNetAgent'] > $best) {
                $best       = $scores['NeuralNetAgent'];
                $bestNeural = $specimen['specimen'];

                //print_r($bestNeural);
                print("Best is " . $best . "\n");
                print("Versus random : \n");
                $decks2 = [];
                for ($i = 0; $i < $numberOfGameToEvaluate; $i++) {
                    $decks2[] = new Deck(new ArrayRandomizer());
                }
                $game2   = new Game([$specimen['specimen'], $randomOpponent], $decks2, new NoDisplayCommandLine(), new ArrayRandomizer());
                $scores2 = $game2->doXGame($numberOfGameToEvaluate);
                print_r($scores2);
            }
            $notAltered[$index]++;
            $populations[$index]['fitness'] = $scores['NeuralNetAgent'];
        }

        foreach ($notAltered as $x => $v) {
            if ($v > 2) {
                print("Progression : " . $x . " -> " . $v . " -> " . $populations[$x]['fitness']) . "\n";
            }
        }

        list($winners, $losers) = tournamentSelection($populations);
        for ($j = 0; $j < count($losers); $j++) {
            $parents = array_rand($winners, 2);
            $child   = croissement($winners[$parents[0]]['specimen'], $winners[$parents[1]]['specimen']);
            if ((((float)rand() / (float)getrandmax())) < $mutationRate) {
                $child->mutate();
            }
            $notAltered[$losers[$j]['id']]  = 0;
            $populations[$losers[$j]['id']] = ['specimen' => $child, 'fitness' => -1];
        }
    }
}

/*
$commandLine = new CommandLine();

$commandLine->sayLine("================== GAME START ===============");



$commandLine->sayLine("================== GAME END ===============");

$winner = $game->getWinner();
$loser = $game->getLoser();

if ($winner->getScore() == $loser->getScore()) {
    $commandLine->sayLine("DRAW !");
}
else {
    $commandLine->sayLine("Winner is {$winner->getName()} with {$winner->getScore()} points !");
    $commandLine->sayLine("Loser is {$loser->getName()} with {$loser->getScore()} points !");
}*/