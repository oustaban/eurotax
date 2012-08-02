<?php
switch($_SERVER ['SERVER_NAME']) {
    case 'eurotax':
    case 'eurotax.testenm.com':
        define('ConfigType', 'dev'); break;
        //define('ConfigType', 'test'); break;
        //define('ConfigType', 'prod'); break;
}