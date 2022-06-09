<?php
/**
 * define the root path
 */
define('ROOT_PATH',dir(__DIR__));
/**
 * first i include the autoload and run it
 */
require ROOT_PATH.'/app/Autoload.php';
require ROOT_PATH.'/vendor/autoload.php';
App\Autoload::loader();

/**
 * initialisation 
 */
include ROOT_PATH.'/global/init.php';

$app->sendResponse();
