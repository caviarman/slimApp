<?php

namespace App\Games\Calc;

use function App\Games\Cli\run;

const DESCRIPTION = 'What is the result of the expression?';
const OPERATORS = ['+', '-', '*'];

function getData()
{
    $index = rand(0, 2);
    $firstNum = rand(1, 100);
    $secondNum = rand(1, 100);
    switch (OPERATORS[$index]) {
        case '+':
            $question = ("{$firstNum} + {$secondNum}");
            $answer = $firstNum + $secondNum;
            break;
        case '-':
            $question = ("{$firstNum} - {$secondNum}");
            $answer = $firstNum - $secondNum;
            break;
        case '*':
            $question = ("{$firstNum} * {$secondNum}");
            $answer = $firstNum * $secondNum;
            break;
        default:
            break;
    }
    return [
       'question' => $question,
        'answer' => (string)$answer
    ];
}


function game()
{
    $calcGame = function () {
        return getData();
    };
    return run($calcGame, DESCRIPTION);
}
