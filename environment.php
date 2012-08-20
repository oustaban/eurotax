<?php
switch($_SERVER ['SERVER_NAME']) {
    case 'eurotax':
        define('ConfigType', 'dev'); break;
//        define('ConfigType', 'test'); break;
//    case 'eurotax.local':
//    case 'eurotax.testenm.com.local':
    case 'eurotax.testenm.com':
        define('ConfigType', 'prod'); break;
}