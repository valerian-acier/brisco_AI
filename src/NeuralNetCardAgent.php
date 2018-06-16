<?php

use BriscolaCLI\Card;
use BriscolaCLI\NeuralNet;
use BriscolaCLI\Player;

/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 03/06/2018
 * Time: 19:58
 */

namespace BriscolaCLI;

class NeuralNetCardAgent extends Player
{

    public $neuralNet;

    private $inData = 40;
    private $outData = 3;
    private $neurones = 10;
    private $layersNB = 2;

    public function __construct($name)
    {
        $this->neuralNet = new NeuralNet($this->inData, $this->outData, $this->neurones, $this->layersNB, 0.1);
        parent::__construct($name);
    }


    /**
     * @param      $playedCard
     * @param Card $currentFirstCard
     * @param Card $currentTrumpCard
     * @return int
     */
    public function getAction($playedCard, $currentFirstCard, Card $currentTrumpCard)
    {
        /// 0 is not played
        /// 1 is already played
        /// 2 is currentFirstCard
        /// 3 is trumpCard
        /// 4 is in hand
        $inData = [];
        foreach (Card::getSuits() as $suit) {
            foreach (Card::getRanks() as $rank) {


                if ($currentFirstCard != null && $currentFirstCard->getSuit() == $suit && $currentFirstCard->getRank() == $rank) {
                    $inData[] = 2;
                    continue;
                }

                $found = false;
                foreach ($playedCard as $card) {
                    /** @var Card $card */
                    if ($card->getRank() == $rank && $card->getSuit() == $suit) {
                        $inData[] = 1;
                        $found    = true;
                        break;
                    }
                }

                if ($found) {
                    continue;
                }

                if ($currentTrumpCard->getSuit() == $suit && $currentTrumpCard->getRank() == $rank) {
                    $inData[] = 3;
                    continue;
                }

                foreach ($this->getCardsInHand() as $card) {
                    /** @var Card $card */
                    if ($card->getRank() == $rank && $card->getSuit() == $suit) {
                        $inData[] = 4;
                        $found    = true;
                        break;
                    }
                }
                if ($found) {
                    continue;
                }

                $inData[] = 0;
            }
        }

        $out   = $this->neuralNet->getOutput($inData);


        $bestI = 0;
        $best  = -99999999;
        foreach ($out as $index => $v) {
            if ($v > $best) {
                $bestI = $index;
                $best  = $v;
            }
        }

        if ($bestI >= count($this->getCardsInHand())) {
            return count($this->getCardsInHand()) - 1;
        }
        #print_r($out);
        return $bestI;
    }


    public function mutate(){
        $this->neuralNet->mutate();
    }

    public function crossing(NeuralNetCardAgent $pere, NeuralNetCardAgent $mere)
    {
        $this->neuralNet = new NeuralNet($this->inData, $this->outData, $this->neurones, $this->layersNB, 0.1);
        $this->neuralNet->childFrom($pere->neuralNet, $mere->neuralNet);
    }

}