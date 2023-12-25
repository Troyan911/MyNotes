<?php

namespace App\Validators\Auth;

use App\Models\User;
use App\Validators\BaseValidator;

class BaseAuthValidator extends BaseValidator
{

    protected array $rules = [
        'email' => '/^[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i',
        'password' => '/[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]{8,}/',
    ];
    public function checkEmailOnExists(string $email, bool $eq = true, string $message = "Email already exists"): bool
    {
        $result = (bool)User::findBy('email', $email);

        if ($result === $eq) {
            $this->setErrors('email', $message);
        }
        return $result;
    }

}