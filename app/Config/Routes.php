<?php

$routes->get('/', 'Home::index');
$routes->get('setting', 'Home::settingPage');
$routes->get('bank-account', 'Home::bankAccountPage');

$routes->post('settings/(:segment)/update', 'Home::updateSetting/$1');


$routes->get('/oauth/callback', 'Oauth::callback');
$routes->get('/oauth/callback-success', 'Oauth::success');