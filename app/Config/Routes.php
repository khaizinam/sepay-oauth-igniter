<?php
/**
 * PAGES WEB
 */
$routes->get('/', 'Home::index');
$routes->get('/test', 'Home::test');
$routes->get('/setting', 'Home::settingPage');
$routes->get('/webhooks', 'Home::webhookPage');
$routes->get('/webhooks/create', 'Home::createWebhookPage');
$routes->get('/webhooks/(:segment)/edit', 'Home::editWebhookPage/$1');
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

/**
 * WEBHOOKS
 */
$routes->post('/api/v1/se-pay/webhooks/leadgen', 'SePay::leadgen');
$routes->get('/api/v1/se-pay/webhooks/list', 'SePay::getWebooks');
$routes->get('/api/v1/se-pay/webhooks/(:segment)', 'SePay::getWebookDetail/$1');
$routes->post('/api/v1/se-pay/webhooks/store', 'SePay::createNewWebhook');
$routes->post('/api/v1/se-pay/webhooks/(:segment)/update', 'SePay::updateWebhook/$1');
$routes->get('/api/v1/se-pay/webhooks/(:segment)/delete', 'SePay::deleteWebhook/$1');