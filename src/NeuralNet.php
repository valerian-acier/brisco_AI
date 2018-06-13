<?php
/**
 * Created by PhpStorm.
 * User: valerian
 * Date: 03/06/2018
 * Time: 20:05
 */

namespace BriscolaCLI;

class NeuralNet
{

    protected $inData, $outData;
    public             $weights;

    //hyperparameters
    protected $layersNB, $learningRate, $neurones;

    public function __construct($inData, $outData, $neurones, $layersNB, $learningRate)
    {
        $this->inData       = $inData;
        $this->outData      = $outData;
        $this->neurones     = $neurones;
        $this->layersNB     = $layersNB;
        $this->learningRate = $learningRate;
        $this->reset();
    }

    public function childFrom(NeuralNet $pere, NeuralNet $mere)
    {
        $ratioMotherFather = 0.5;
        for ($layoutIndex = 0; $layoutIndex < count($this->weights); $layoutIndex++) {
            for ($neuroneIndex = 0; $neuroneIndex < count($this->weights[$layoutIndex]); $neuroneIndex++) {
                for ($connexionIndex = 0; $connexionIndex < count($this->weights[$layoutIndex][$neuroneIndex]); $connexionIndex++) {
                    if ((((float)rand() / (float)getrandmax())) < $ratioMotherFather) {
                        $this->weights[$layoutIndex][$neuroneIndex][$connexionIndex] = $mere->weights[$layoutIndex][$neuroneIndex][$connexionIndex];
                    } else {
                        $this->weights[$layoutIndex][$neuroneIndex][$connexionIndex] = $pere->weights[$layoutIndex][$neuroneIndex][$connexionIndex];
                    }
                }
            }
        }
    }

    public
    function generateRandomArrayBetweenValues(
        $arraySize, $min, $max
    ) {
        $array = [];
        for ($x = 0; $x < $arraySize; $x++) {
            $array[] = (((float)rand() / (float)getrandmax()) * ($max - $min)) + $min;
        }

        return $array;
    }

    public
    function mutate()
    {
        $mutationFactor = (((float)rand() / (float)getrandmax()) * 0.3);
        foreach ($this->weights as $layoutIndex => $layout) {
            foreach ($layout as $neuroneIndex => $neurones) {
                foreach ($neurones as $connexionIndex => $connexions) {
                    if ((float)rand() / (float)getrandmax() < $mutationFactor) {
                        $this->weights[$layoutIndex][$neuroneIndex][$connexionIndex] += (((float)rand() / (float)getrandmax()) * (0.02)) - 0.01;
                    }
                }
            }
        }
    }

    public
    function reset()
    {
        $this->weights = [];
        // Hidden layers initialisation
        for ($i = 0; $i < $this->layersNB; $i++) {
            $layer = [];
            for ($j = 0; $j < $this->neurones; $j++) {
                $layerSize = $this->neurones + 1;
                if ($i == 0) {
                    $layerSize = $this->inData + 1;
                }
                $layer[] = $this->generateRandomArrayBetweenValues($layerSize, -1, 1);
            }
            $this->weights[] = $layer;
        }

        //Out layer

        $layer = [];
        for ($j = 0; $j < $this->outData; $j++) {
            $layer[] = $this->generateRandomArrayBetweenValues($this->neurones + 1, -1, 1);
        }
        $this->weights[] = $layer;
    }

    public
    function activation(
        $value
    ) {
        if ($value < -709) {
            return 1;
        }

        return 1 / (1 + exp(-$value));
    }

    public
    function getOutput(
        $inData
    ) {

        $inData[] = 1;

        foreach ($this->weights as $indexLayer => $layer) {
            $outData = [];
            foreach ($layer as $neurone) {
                $neuralsValues = [];
                for ($index = 0; $index < count($inData); $index++) {
                    $neuralsValues[] = $inData[$index] * $neurone[$index];
                }
                $outData[] = $this->activation(array_sum($neuralsValues));
            }
            $inData = $outData;
            if ($indexLayer != count($this->weights) - 1) {
                $inData[] = 1;
            }
        }

        return $inData;
    }
}