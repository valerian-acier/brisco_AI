<?php
/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 05/06/2018
 * Time: 21:42
 */

namespace BriscolaCLI;


class ArrayNotRandomizer extends ArrayRandomizer
{
    public function randomize(array $array)
    {
        //$shuffled = $array;
        //$shuffled = array_reverse($shuffled);

        return $array;
    }

    public function pickRandom(){
        return 0;
    }
}