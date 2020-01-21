<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../src/Log.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

# use monolog wrapper
$monolog = \LogSample::getMonoLog();
$monolog->notice("Hello!" . microtime());
?>

logged, see console!!