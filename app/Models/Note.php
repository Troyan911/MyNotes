<?php

namespace App\Models;

use Core\Model;

class Note extends Model
{
    protected static string|null $tableName = 'notes';

    public int $user_id, $folder_id;
    public string|null $title, $content;
    public bool $pinned, $completed;

    public function getInfo(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'pinned' => $this->pinned,
            'completed' => $this->completed
        ];
    }

}