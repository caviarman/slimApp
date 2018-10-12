<?php

namespace App;

class Validator
{
    public function validate(array $user)
    {
        $errors = [];
        if ($user['name'] == '') {
            $errors['name'] = "Can't be blank";
        }

        $arr = str_split($user['password']);
        if (count($arr) < 8) {
            $errors['password'] = "Can't be less than 8 simbols";
        }

        return $errors;
    }
}
