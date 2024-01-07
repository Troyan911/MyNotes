<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
//    todo parse class name
//    change name from User to users via regexp
    protected static string|null $tableName = 'users';

    public string | null $email, $password, $token, $created_at, $token_expired_at = null;

    public function getUserInfo(): array
    {
        return [
            "email" => $this->email,
            'token' => $this->token
        ];
    }

}