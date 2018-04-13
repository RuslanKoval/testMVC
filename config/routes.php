<?php

$routes = array(
    '/' => 'application#index',
    '/register' => 'user#register',
    '/confirm' => 'user#confirm',
    '/login' => 'application#login',
    '/logout' => 'application#logout',
    '/profile' => 'user#profile',
    '/profile/edit' => 'user#edit',
);