<?php

use BriscolaCLI\ArrayRandomizer;
use BriscolaCLI\Card;
use BriscolaCLI\Deck;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function it_has_40_cards_at_start()
    {
        $deck = new Deck(new ArrayRandomizer());

        $this->assertEquals(40, $deck->getCardsLeftCount());
    }

    /** @test */
    public function can_take_one_card_off_the_deck()
    {
        $deck = new Deck(new ArrayRandomizer());

        $card = $deck->draw();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals(39, $deck->getCardsLeftCount());
    }

    /** @test */
    public function cards_should_be_randomized_on_construction()
    {
        $randomizerMock = Mockery::mock(ArrayRandomizer::class);
        $randomizerMock->shouldReceive('randomize')->once()->andReturn([]);

        $deck = new Deck($randomizerMock);

        $this->assertEquals(0, $deck->getCardsLeftCount());
    }

    /** @test */
    public function can_draw_multiple_cards_at_once()
    {
        $deck = new Deck(new ArrayRandomizer());

        $deck->draw(40);

        $this->assertEquals(0, $deck->getCardsLeftCount());
    }
}