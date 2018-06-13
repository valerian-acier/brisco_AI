<?php
/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 03/06/2018
 * Time: 23:31
 */

namespace BriscolaCLI;

class NoDisplayCommandLine extends CommandLine
{
    public function getLine($prompt)
    {
    }

    public function say($text)
    {
    }

    public function sayLine($text)
    {
    }

}