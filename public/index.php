<?php

require_once dirname(__DIR__) . "/core/SourceLoader.php";

//dd(TEST);

use Core\Router;
use \App\Models\User;
use \App\Models\Folder;
use \App\Models\Note;

try {
    Folder::where('id', '=', 2);

//    dd(User::select()->where('id', 3)->get());

//    d(User::create([
//        'email'=>'qwe4@i.ua',
//        'password' => 'qwerty4'
//    ]));
//
//    $users = User::select()->get();
//
//    foreach ($users as $user) {
//        d($user->getUserInfo());
//    }
//
//    $folders = Folder::select()->get();
//    foreach ($folders as $folder) {
//        d($folder->getInfo());
//    }
//
//    $notes = Note::select()->get();
//    foreach ($notes as $note) {
//        d($note->getInfo());
//    }

//    if (!preg_match("/assets/i", $_SERVER['REQUEST_URI'])) {
    echo Router::dispatch($_SERVER['REQUEST_URI']);
    exit;
//    }

} catch (PDOException $exception) {
    errorResponse($exception);
}
