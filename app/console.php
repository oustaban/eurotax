<pre><?php
$script = 'console';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$params = array_key_exists('PATH_INFO', $_SERVER) ? $_SERVER["PATH_INFO"] : '';
$params = trim($params, "/ \0\n\r\t");

echo shell_exec("php " . $script . " " . $params);