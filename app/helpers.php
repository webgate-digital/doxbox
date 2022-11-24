<?php

if(!function_exists('is_b2b')) {
    function is_b2b(): bool {
        $me = session()->get('me');

        if(!$me) {
            return false;
        }
        return in_array('b2b', $me['roles']) && $me['b2b_approved'];
    }
}