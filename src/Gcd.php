<?php

namespace App;

class Gcd
{
    public function gcd($firstNum, $secondNum)
    {
        if ($secondNum === 0) {
            return $firstNum;
        }
        return $this->gcd($secondNum, $firstNum % $secondNum);
    }

    public function getData()
    {
        $firstNum = rand(1, 100);
        $secondNum = rand(1, 100);
        $question = ("{$firstNum} {$secondNum}");
        $answer = $this->gcd($firstNum, $secondNum);
        return [
            'question' => $question,
            'answer' => (string)$answer,
            'description' => 'Find the greatest common divisor of given numbers.'
        ];
    }
}
