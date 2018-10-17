<?php

namespace App;

class Even
{
    public function isEven($num)
    {
        return $num % 2 === 0;
    }

    public function getData()
    {
        $question = rand(1, 100);
        $answer = $this->isEven($question) ? 'yes' : 'no';
        return [
            'question' => $question,
            'answer' => $answer,
            'description' => 'Answer "yes" if number even otherwise answer "no".'
        ];
    }
}
