<?php

use BriscolaCLI\Card;
use BriscolaCLI\CardsWinner;
use PHPUnit\Framework\TestCase;

class CardsWinnerTest extends TestCase
{
    /** @test */
    public function trump_card_always_win_against_not_trump_cards()
    {
        $trump = new Card('trump', 'rank');
        $winner = new Card('trump', '2');
        $cards = [$winner, new Card('not_trump', 'A')];

        $cardsWinner = new CardsWinner();

        $this->assertEquals($winner, $cardsWinner->getWinningCard($cards, $trump));
    }

    /** @test */
    public function biggest_trump_card_wins_when_all_cards_are_trump_cards()
    {
        $trump = new Card('trump', 'rank');
        $winner = new Card('trump', 'A');
        $cards = [$winner, new Card('trump', '8')];

        $cardsWinner = new CardsWinner();

        $this->assertEquals($winner, $cardsWinner->getWinningCard($cards, $trump));
    }

    /** @test */
    public function same_suits_highest_card_wins()
    {
        $trump = new Card('trump', 'rank');
        $winner = new Card('coin', 'A');
        $cards = [new Card('coin', '8'), $winner];

        $cardsWinner = new CardsWinner();

        $this->assertEquals($winner, $cardsWinner->getWinningCard($cards, $trump));
    }

    /** @test */
    public function different_suit_first_card_played_wins()
    {
        $trump = new Card('trump', 'rank');
        $winner = new Card('sword', 'A');
        $cards = [$winner, new Card('coin', '8')];

        $cardsWinner = new CardsWinner();

        $this->assertEquals($winner, $cardsWinner->getWinningCard($cards, $trump));
    }
}