<?php

namespace App;

class Prime
{
    protected function iter($acc, $n)
    {
        if ($acc > $n / 2) {
            return true;
        }
        if ($n % $acc === 0) {
            return false;
        }
        return $this->iter($acc + 1, $n);
    }

    public function isPrime($num)
    {
        if ($num < 2) {
            return false;
        }
        return $this->iter(2, $num);
    }

    public function getData()
    {
        $question = rand(2, 100);
        $answer = $this->isPrime($question) ? 'yes' : 'no';
        return [
            'question' => $question,
            'answer' => $answer,
            'description' => 'Answer "yes" if given number is prime. Otherwise answer "no".'
        ];
    }
}
