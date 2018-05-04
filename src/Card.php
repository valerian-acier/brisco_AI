<?php

namespace BriscolaCLI;

class Card
{
    /**
     * @var string
     */
    private $suit;
    /**
     * @var string
     */
    private $rank;

    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public static function getSuits()
    {
        return [
            'cup',
            'coin',
            'sword',
            'stick'
        ];
    }

    public static function getRanks()
    {
        return [
            'A',
            'K',
            'Q',
            'J',
            '7',
            '6',
            '5',
            '4',
            '3',
            '2'
        ];
    }
}