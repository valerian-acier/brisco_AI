<?php

namespace BriscolaCLI;

/**
 * Class Card
 * @package BriscolaCLI
 */
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

    /**
     * Card constructor.
     * @param string $suit
     * @param string $rank
     */
    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    /**
     * @return array
     */
    public static function getSuits()
    {
        return [
            'cup',
            'coin',
            'sword',
            'stick'
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return int
     */
    public function getPointsValue(): int
    {
        switch ($this->rank) {
            case 'A':
                return 11;
                break;
            case '3':
                return 10;
                break;
            case 'K':
                return 4;
                break;
            case 'Q':
                return 3;
                break;
            case 'J':
                return 2;
                break;
            default:
                return 0;
        }
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function __toString()
    {
        return "{$this->rank}:{$this->suit}";
    }
}