<?php

$routes->get('/', 'Home::index');
$routes->get('/setting', 'Home::settingPage');
$routes->get('/bank-account', 'Home::bankAccountPage');

$routes->post('settings/(:segment)/update', 'Home::updateSetting/$1');

$routes->get('/oauth/callback', 'Oauth::callback');
$routes->get('/oauth/callback-success', 'Oauth::success');


$routes->get('/api/v1/se-pay/bank-account/(:segment)', 'SePay::getBankDetails/$1');
$routes->get('/api/v1/se-pay/bank-account/(:segment)/sub-accounts', 'SePay::getSubAccount/$1');
$routes->post('/api/v1/se-pay/bank-account/update', 'SePay::updateBankAcount');
