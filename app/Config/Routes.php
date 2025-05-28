<?php

$routes->get('/', 'Home::index');

$routes->get('/oath/callback', 'Oauth::callback');
$routes->get('/oath/callback-success', 'Oauth::success');