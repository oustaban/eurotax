<?php
if (defined('CHECK_ACCESS') && CHECK_ACCESS) {
// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it, or make something more sophisticated.
    if (!preg_match("/192\.168\.0/", $_SERVER['REMOTE_ADDR'])
        && !in_array(@$_SERVER['REMOTE_ADDR'], array(
            '195.140.169.238', //Kiev office
            '88.188.160.112', //Pierre home
            '62.193.54.208', //Pierre Masao
            '79.84.136.155', //Gilles
            '127.0.0.1',
            '::1',
        ))
    ) {
        header('HTTP/1.0 403 Forbidden');
        exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
    }
}