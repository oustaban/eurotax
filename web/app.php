<?php
umask(0002);
// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it, or make something more sophisticated.
require_once ROOT_PATH . '/checkIP.php';

require_once APP_PATH . '/bootstrap.php.cache';
require_once APP_PATH . '/AppKernel.php';
if (APPLICATION_CACHE_MODE) {
//    require_once APP_PATH . '/AppCache.php';
}

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel(APP_KERNEL_ENVIRONMENT, APPLICATION_ERROR_MODE);
$kernel->loadClassCache();
$kernel->handle(Request::createFromGlobals())->send();
