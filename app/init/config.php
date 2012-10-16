<?php

class Config
{

    public static function run()
    {
        self::setupEnvironment();
        self::setupPhpIni();
        self::setupFirePHP();
    }

    public static function setupEnvironment()
    {
        if (defined('APPLICATION_ENVIRONMENT'))
            return false;

        //init APPLICATION_ENVIRONMENT

        if (isset($_GET['aen'])) {
            $configType = $_GET['aen'];
        } elseif (!defined('ConfigType')) {
            $configType = 'prod';
            if (isset($_SERVER ['SERVER_NAME'])) {
                $esn = explode('.', $_SERVER ['SERVER_NAME']);
                $c = count($esn) - 1;
                $d = $esn[$c - 1] . '.' . $esn[$c];
                switch ($esn[$c]) {
                    case 'local':
                        $configType = 'dev';
                        break;
                    case 'test':
                        $configType = 'test';
                        break;
                }
            }
        } else {
            $configType = ConfigType;
        }

        //init APPLICATION_ERROR_MODE
        if (isset($_GET['aem'])) {
            $errorMode = $_GET['aem'];
        } else {
            switch ($configType) {
                case 'cron':
                case 'test':
                case 'dev':
                    $errorMode = true;
                    break;
                default:
                    $errorMode = false;
            }
        }

        //init APPLICATION_FIREPHP_MODE
        if (isset($_GET['afm'])) {
            $firePhpMode = $_GET['afm'];
        } else {
            switch ($configType) {
                case 'test':
                case 'dev':
                    $firePhpMode = true;
                    break;
                default:
                    $firePhpMode = false;
            }
        }

        //init APPLICATION_CACHE_MODE
        if (isset($_GET['acm'])) {
            $cacheMode = $_GET['acm'];
        } else {
            switch ($configType) {
                case 'cron':
                case 'dev':
                    $cacheMode = false;
                    break;
                default:
                    $cacheMode = true;
            }
        }

        //init APPLICATION_COMPRESS_MODE
        if (isset($_GET['amm'])) {
            $compressMode = $_GET['amm'];
        } else {
            switch ($configType) {
                case 'cron':
                case 'dev':
                    $compressMode = false;
                    break;
                default:
                    $compressMode = true;
            }
        }

        //init VIEW_EXTRAINFO_MODE
        if (isset($_GET['vem'])) {
            $extraInfoMode = $_GET['vem'];
        } else {
            switch ($configType) {
                case 'test':
                case 'dev':
                    $extraInfoMode = true;
                    break;
                default:
                    $extraInfoMode = false;
            }
        }

        //init APP_KERNEL_ENVIRONMENT
        if (isset($_GET['ake'])) {
            $extraInfoMode = $_GET['ake'];
        } else {
            switch ($configType) {
                case 'test':
                case 'dev':
                case 'cron':
                    $kernelEnvironment = 'dev';
                    break;
                default:
                    $kernelEnvironment = 'prod';
            }
        }

        switch ($configType) {
            case 'dev':
                $checkAccess = true;
                break;
            default:
                $checkAccess = false;
        }

        define('APPLICATION_ENVIRONMENT', $configType);
        define('APPLICATION_ERROR_MODE', $errorMode);
        define('APPLICATION_FIREPHP_MODE', $firePhpMode);
        define('APPLICATION_CACHE_MODE', $cacheMode);
        define('APPLICATION_COMPRESS_MODE', $compressMode);
        define('VIEW_EXTRAINFO_MODE', $extraInfoMode);
        define('APP_KERNEL_ENVIRONMENT', $kernelEnvironment);
        define('CHECK_ACCESS', $checkAccess);
    }

    public static function setupPhpIni()
    {
        if (APPLICATION_ERROR_MODE) {
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            /* ini_set('xdebug.remote_enable',1);
              ini_set('xdebug.remote_autostart','On');

              ini_set('xdebug.profiler_enable','On');
              ini_set('xdebug.profiler_output_dir',"C:\Program Files\Zend\ZendServer\tmp\xdebug\cachegrid");
              ini_set('xdebug.profiler_append','On');
              ini_set('xdebug.profiler_output_name',"%t.cachegrind.out");

              ini_set('xdebug.auto_trace','On');
              ini_set('xdebug.trace_format',0);
              ini_set('xdebug.collect_params',1);
              ini_set('xdebug.collect_return', 1);
              ini_set('xdebug.collect_includes',1);
              ini_set('xdebug.trace_options',1);
              ini_set('xdebug.trace_output_dir',"C:\Program Files\Zend\ZendServer\tmp\xdebug\trace");
              ini_set('xdebug.trace_output_name',"%t.trace.xt");
              ini_set('xdebug.show_local_vars',1);
              ini_set('xdebug.show_local_vars','On');
              //ini_set('xdebug.dump.SERVER','HTTP_HOST, SERVER_NAME');
              ini_set('xdebug.dump_globals','On');
              ini_set('xdebug.collect_params','4'); */

            //ini_set('xdebug.show_exception_trace','On');
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }
    }

    public static function setupFirePHP()
    {
        if (APPLICATION_FIREPHP_MODE) {
        }
    }
}

Config::run();