<?php

use BriscolaCLI\ArrayRandomizer;
use BriscolaCLI\Card;
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
        $game = new Game([$player1, $player2], $deck);
        $game->start();

        $this->assertTrue($game->isRunning());
        $this->assertInstanceOf(Card::class, $game->getTrumpCard());
    }

    /** @test */
    public function dealing_to_players_should_give_them_up_to_3_cards()
    {
        $player1 = new Player('Johnny');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1], $deck);

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
        $game = new Game([$player1, $player2], $deck);
        $game->start();

        $this->assertInstanceOf(Player::class, $game->getNextPlayerToAct());
    }

    /** @test */
    public function when_there_is_no_card_left_in_deck_then_it_should_not_throw_an_error_and_not_draw_anything()
    {
        $player1 = new Player('Johnny');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1], $deck);
        $this->assertEquals(0, count($player1->getCardsInHand()));
        $deck->draw(40);
        $game->deal();
        $this->assertEquals(0, count($player1->getCardsInHand()));
    }

    /** @test */
    public function the_winner_is_the_one_player_with_the_most_points_and_the_loser_is_the_other_one()
    {
        $player1 = new Player('Johnny');
        $player2 = new Player('Mary');
        $deck = new Deck(new ArrayRandomizer());
        $game = new Game([$player1, $player2], $deck);

        $player1->addCardsWon([new Card(Card::getSuits()[0], 'A'), new Card(Card::getSuits()[0], '2')]);

        $this->assertEquals($player1, $game->getWinner());
        $this->assertEquals($player2, $game->getLoser());
    }
}