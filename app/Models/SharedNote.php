<?php

namespace App\Models;

use Core\Model;

class SharedNote extends Model
{
    protected static string|null $tableName = 'shared_notes';

    public int $user_id, $note_id;

}