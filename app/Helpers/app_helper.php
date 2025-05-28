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