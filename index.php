<?php
require_once 'env.php';
require_once 'config.php';

error_reporting(ERROR_REPORTING);
ini_set('display_errors', DISPLAY_ERRORS);
ini_set('display_startup_errors', DISPLAY_STARTUP_ERRORS);
date_default_timezone_set(DEFAULT_TIMEZONE);
setlocale(LC_ALL, DEFAULT_LOCALE);

require_once 'vendor/autoload.php';

session_start();

$system = new simphplio\System();
$system->run();