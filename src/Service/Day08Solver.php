<?php

namespace App\Service;

class Day08Solver extends AbstractDaySolver
{
    public function part1()
    {
        $imageData = $this->getInputFormated();
        $width = 25;
        $height = 6;
        $dataCount = count($imageData);
        $layers = $dataCount / ($width * $height);

        $this->logger->info("$dataCount => $layers Layers");

        $image = [];
        $minZeroLayer = 0;
        $minZeros = 9999;
        for ($l = 0; $l < $layers; $l++) {
            $zerosInLayer = 0;
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $p = $y * $width + $x + $width * $height * $l;
                    $v = $imageData[$p];
                    if ($v == 0) {
                        $zerosInLayer += 1;
                    }
                    $image[$l][$y][$x] = $v;
                }
            }
            if ($zerosInLayer < $minZeros) {
                $minZeros = $zerosInLayer;
                $minZeroLayer = $l;
            }
        }

        $ones = 0;
        $twoes = 0;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($image[$minZeroLayer][$y][$x] == 1) {
                    $ones += 1;
                }
                if ($image[$minZeroLayer][$y][$x] == 2) {
                    $twoes += 1;
                }
            }
        }

        return $ones * $twoes;
    }

    public function part2()
    {
        $imageData = $this->getInputFormated();
        $width = 25;
        $height = 6;
        $dataCount = count($imageData);
        $layers = $dataCount / ($width * $height);

        $image = [];
        for ($l = 0; $l < $layers; $l++) {
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $p = $y * $width + $x + $width * $height * $l;
                    $v = $imageData[$p];
                    $image[$l][$y][$x] = $v;
                }
            }
        }

        $render = '';
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                for ($l = 0; $l < $layers; $l++) {
                    if ($image[$l][$y][$x] != 2) {
                        if ($image[$l][$y][$x]) {
                            $render .= '#';
                        } else {
                            $render .= ' ';
                        }
                        break;
                    }
                }
            }
            $render .= "\n";
        }

        return "<pre>$render</pre>";
    }

    /**
    * @return array
    */
    protected function getInputFormated()
    {
        $data = parent::getInputFormated();
        return str_split(implode('', $data));
    }
}
