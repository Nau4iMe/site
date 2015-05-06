<?php

Validator::extend('alpha_num_spaces', function($attribute, $value) {
    return preg_match('/^[\pL\pN\s\(\)_\'\"\.\+-:#]+$/u', $value);
});

Validator::extend('youtube', function($attribute, $value) {
    return preg_match('/\b[a-zA-Z0-9_-]{11}\b/', $value);
});
