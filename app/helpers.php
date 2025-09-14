<?php

if (!function_exists('t')) {
    function t($key, $default = null) {
        return \App\Helpers\TranslationHelper::t($key, $default);
    }
}
