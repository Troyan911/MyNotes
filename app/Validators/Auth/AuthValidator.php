<?php

namespace App\Validators\Auth;

use App\Validators\BaseValidator;

class AuthValidator extends BaseAuthValidator
{

    const DEFAULT_MESSAGE = "Email or password is incorrect";

    protected array $errors = [
        'email' => self::DEFAULT_MESSAGE,
        'password' => self::DEFAULT_MESSAGE,
    ];

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            !$this->checkEmailOnExists($fields['email'], false, self::DEFAULT_MESSAGE,)
        ];

        return !in_array(false, $result);
    }
}