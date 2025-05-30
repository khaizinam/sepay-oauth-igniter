<?php
/**
 * PAGES WEB
 */
$routes->get('/', 'Home::index');
$routes->get('/setting', 'Home::settingPage');
$routes->get('/bank-account', 'Home::bankAccountPage');
$routes->get('/transactions', 'Home::transactionsPage');
/**
 * SEPAY OAUTH CALLBACK
 */
$routes->get('/oauth/callback', 'Oauth::callback');
$routes->get('/oauth/callback-success', 'Oauth::success');

/**
 * API ROUTES
 */
$routes->post('settings/(:segment)/update', 'Home::updateSetting/$1');
$routes->get('/api/v1/se-pay/transactions', 'SePay::getTransactions');
$routes->get('/api/v1/se-pay/bank-account/(:segment)', 'SePay::getBankDetails/$1');
$routes->get('/api/v1/se-pay/bank-account/(:segment)/sub-accounts', 'SePay::getSubAccount/$1');
$routes->post('/api/v1/se-pay/bank-account/update', 'SePay::updateBankAcount');
