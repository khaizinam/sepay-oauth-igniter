<?php

$routes->get('/', 'Home::index');

$routes->get('/oauth/callback', 'Oauth::callback');
$routes->get('/oauth/callback-success', 'Oauth::success');