<?php

namespace Core;
abstract class Controller
{
    public function before(string $action, array $params = []): bool
    {
        return true;
    }

    public function after(string $action): bool
    {
        return true;
    }

    protected function response(int $code = 200, array $body = [], array $errors = []): array
    {
//        return [
//            'code' => $code,
//            'data' => $body,
//            'errors' => $errors
//        ];

        return compact('code', 'body', 'errors');
    }

}
