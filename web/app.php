<?php

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

if (CHECK_ACCESS) {
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

require_once APP_PATH . '/bootstrap.php.cache';
require_once APP_PATH . '/AppKernel.php';
if (APPLICATION_CACHE_MODE) {
//    require_once APP_PATH . '/AppCache.php';
}

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel(APP_KERNEL_ENVIRONMENT, APPLICATION_ERROR_MODE);
$kernel->loadClassCache();
$kernel->handle(Request::createFromGlobals())->send();
