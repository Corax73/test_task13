<?php

use Classes\Santa;
use Classes\User;

if (isset($_POST['email']) && $_POST['email']) {
    $santa = new Santa();
    $santas = collect($santa->all())->pluck('santas', 'gifted');
    if ($santas->isEmpty()) {
        $user = new User();
        $userIds = collect($user->all())->pluck('email')->toArray();
        $santas = $santa->createSantas($userIds);
        foreach ($santas as $key => $value) {
            $santa->save($key, $value);
        }
        $santas = collect($santa->all())->pluck('santas', 'gifted');
    }
    $resp = $santa->checkWhoseSantaYouAre($_POST['email'], $santas->toArray());
    if ($resp) {
        $message['santa'] = "'You have been chosen as a secret santa for $resp";
    } else {
        $message['no_santa'] = 'No information for your email';
    }
}
