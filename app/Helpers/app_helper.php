<?php

use App\Models\OauthModel;

if(!function_exists('blank')){
    function blank($value){
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_array($value)) {
            return empty($value);
        }

        if (is_object($value)) {
            return empty((array) $value);
        }

        return empty($value);
    }
}

if(!function_exists('app_create_expires_in')){
    function app_create_expires_in($value){
        if (is_numeric($value)) {
            return date('Y-m-d H:i:s', time() + $value);
        }
    }
}
if(!function_exists('sepay_refresh_access_token')){
    function sepay_refresh_access_token($setting){
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('post', 'https://my.sepay.vn/oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type'        => 'refresh_token',
                    'refresh_token'     => $setting['refresh_token'],
                    'client_id'         => $setting['client_id'],
                    'client_secret'     => $setting['client_secret'],
                ]
            ]);
            $body = $response->getBody();
            return json_decode($body, true);
        } catch (\Throwable $th) {
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getMessage());
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getTraceAsString());
            return null;
        }
    }
}

if(!function_exists('sepay_get_access_token')){
    function sepay_get_access_token(){
        try {
            $model = new OauthModel();
            $setting = $model->where('key', 'se-pay')->first();
            if (blank($setting)) {
                return null;
            }
            if (strtotime($setting['expires_in']) < time()) {
                $newToken = sepay_refresh_access_token($setting);
                if (blank($newToken)) {
                    return null;
                }
                $model->update($setting['id'], [
                    'access_token' => $newToken['access_token'],
                    'refresh_token' => $newToken['refresh_token'],
                    'expires_in' => app_create_expires_in($newToken['expires_in']),
                ]);
                return $newToken['access_token'];
            }
            return $setting['access_token'];
        } catch (\Throwable $th) {
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getMessage());
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getTraceAsString());
            return null;
        }
    }
}