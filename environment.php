<?php
switch($_SERVER ['SERVER_NAME']) {
    case 'eurotax':
    case 'eurotax.testenm.com':
        define('ConfigType', 'dev'); break;
//        define('ConfigType', 'test'); break;
//    case 'eurotax.local':
//    case 'eurotax.testenm.com.local':
//        define('ConfigType', 'prod'); break;

}