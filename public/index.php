<?php

require_once dirname(__DIR__) . "/core/SourceLoader.php";

//dd(TEST);

use Core\Router;
use \App\Models\User;
use \App\Models\Folder;
use \App\Models\Note;

try {

    $users = User::select()->get();

    foreach ($users as $user) {
        d($user->getUserInfo());
    }

    $folders = Folder::select()->get();
    foreach ($folders as $folder) {
        d($folder->getInfo());
    }

    $notes = Note::select()->get();
    foreach ($notes as $note) {
        d($note->getInfo());
    }

    if (!preg_match("/assets/i", $_SERVER['REQUEST_URI'])) {
        Router::dispatch($_SERVER['REQUEST_URI']);
        dd($_SERVER);
    }

} catch (PDOException $exception) {
    dd("PDOException", $exception->getMessage());
} catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}

phpinfo();
