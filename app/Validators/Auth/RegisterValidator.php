<?php

namespace App\Validators\Auth;

use App\Validators\BaseValidator;

class RegisterValidator extends BaseAuthValidator
{

    const DEFAULT_MESSAGE = "Email or password is incorrect";

    protected array $errors = [
        'email' => "Email is incorrect",
        'password' => "Password is incorrect. Min length 8 symbols"
    ];

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            !$this->checkEmailOnExists($fields['email'])
        ];

        return !in_array(false, $result);
    }
}