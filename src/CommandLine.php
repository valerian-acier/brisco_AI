<?php

namespace BriscolaCLI;

class CommandLine
{
    public function getLine($prompt)
    {
        echo $prompt . "> ";
        return trim(fgets($this->getStandardInput()));
    }

    public function say($text)
    {
        echo $text;
    }

    public function sayLine($text)
    {
        echo $text . "\n";
    }

    private function getStandardInput()
    {
        return fopen('php://stdin', 'r');
    }
}