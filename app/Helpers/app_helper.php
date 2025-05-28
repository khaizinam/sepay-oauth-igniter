<?php

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