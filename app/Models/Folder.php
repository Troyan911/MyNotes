<?php

namespace App\Models;

use Core\Model;

class Folder extends Model
{
    protected static string|null $tableName = 'folders';

    public int $user_id;
    public string $title, $created_at, $updated_at;

    public function getInfo(): array
    {
        return [
            'title' => $this->title
        ];
    }

}