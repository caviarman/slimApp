<?php

namespace App;

class Progression
{
    protected $size = 10;

    public function getData()
    {
        $index = rand(0, $this->size - 1);
        $start = rand(1, 100);
        $step = rand(1, 100);
        $arr = [];
        for ($i = 0; $i < $this->size; $i += 1) {
            $arr[$i] = $start + ($step * $i);
            $start += $step;
        }
        $answer = $arr[$index];
        $arr[$index] = '..';
        $question = implode(' ', $arr);
    
        return [
            'question' => $question,
            'answer' => (string)$answer,
            'description' => 'What number is missing in this progression?'
        ];
    }
}
