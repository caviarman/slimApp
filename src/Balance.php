<?php

namespace App;

class Balance
{
    public function getArrFromNum($num)
    {
        return str_split((string)$num);
    }

    public function getBalancedNum($num)
    {
        $arr = $this->getArrFromNum($num);
        $size = count($arr);
        $points = array_reduce($arr, function ($acc, $item) {
            return $acc + (int)$item;
        }, 0);
        $baseNum = (int)($points / $size);
        $restNum = $points % $size;
        $balancedArr = array_map(function ($item) use ($baseNum) {
            return (int)$item - (int)$item + $baseNum;
        }, $arr);
        if ($restNum > 0) {
            for ($i = 0; $i < $size; $i += 1) {
                $balancedArr[$i] += 1;
                $restNum -= 1;
                if ($restNum === 0) {
                    break;
                }
            }
        }
        return implode('', array_reverse($balancedArr));
    }

    public function getData()
    {
        $question = rand(100, 1000);
        $answer = $this->getBalancedNum($question);
        return [
            'question' => $question,
            'answer' => $answer,
            'description' => 'Balance the given number.'
        ];
    }
}
