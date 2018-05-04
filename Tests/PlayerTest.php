<?php

use BriscolaCLI\Card;
use BriscolaCLI\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /** @test */
    public function player_has_cards_left_when_still_has_cards_in_hand()
    {
        $player = new Player();
        $player->addCardInHand(new Card(Card::getSuits()[0], Card::getRanks()[0]));

        $this->assertTrue($player->hasCardsLeft());
    }
}