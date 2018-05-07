<?php

use BriscolaCLI\ArrayRandomizer;
use BriscolaCLI\Card;
use BriscolaCLI\CommandLine;
use BriscolaCLI\Deck;
use BriscolaCLI\Game;
use BriscolaCLI\Player;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /** @test */
    public function it_is_still_running_while_players_have_cards_in_hand()
    {
        $player1 = new Player('Johnny');
        $player2 = new Player('Mary');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1, $player2], $deck, new FakeCommandLine());
        $game->start();

        $this->assertTrue($game->isRunning());
        $this->assertInstanceOf(Card::class, $game->getTrumpCard());
    }

    /** @test */
    public function dealing_to_players_should_give_them_up_to_3_cards()
    {
        $player1 = new Player('Johnny');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1], $deck, new FakeCommandLine());

        $this->assertEquals(0, count($player1->getCardsInHand()));
        $game->deal();
        $this->assertEquals(3, count($player1->getCardsInHand()));
    }

    /** @test */
    public function next_player_to_play_should_be_set_when_starting_the_game()
    {
        $player1 = new Player('Johnny');
        $player2 = new Player('Mary');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1, $player2], $deck, new FakeCommandLine());
        $game->start();

        $this->assertInstanceOf(Player::class, $game->getNextPlayerToAct());
    }

    /** @test */
    public function the_winner_is_the_one_player_with_the_most_points_and_the_loser_is_the_other_one()
    {
        $player1 = new Player('Johnny');
        $player2 = new Player('Mary');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1, $player2], $deck, new FakeCommandLine());

        $player1->addCardsWon([new Card(Card::getSuits()[0], 'A'), new Card(Card::getSuits()[0], '2')]);

        $this->assertEquals($player1, $game->getWinner());
        $this->assertEquals($player2, $game->getLoser());
    }

    /** @test */
    public function play_one_turn()
    {
        /**
         * - while all players did not play
         *     - get the next player to act
         *     - tell him his cards with code (Acu (1), 2sw (2), 3cu (3)) and the briscola card
         *     - he picks a code
         *     - game->addCardPlayed($cardPicked)
         *     - game->playerPlayed()
         * - reset player played
         * - choose winner of hand
         * - winner->addCardsWon($cardsPlayed)
         * - $cardsPlayed = []
         * - game next player to act = winner
         */

        $player1 = new Player('Johnny');
        $player2 = new Player('Mary');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1, $player2], $deck, new FakeCommandLine());
        $game->start();

        $game->play();

        $this->assertCount(2, $player1->getCardsInHand());
        $this->assertCount(2, $player2->getCardsInHand());
    }
}

class FakeCommandLine extends CommandLine
{
    public function getLine($prompt)
    {
        return 1;
    }
}