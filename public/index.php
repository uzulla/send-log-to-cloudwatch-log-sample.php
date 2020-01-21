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

Notice logged, see console!!<br>
<br>
... and throw exception.
<?php
try{
    throw new \Exception("Woooo I am Exception!!");
}catch(\Exception $e){
    $monolog = \LogSample::getMonoLog();
    $monolog->error(
        "I got exception",
        [
            'message'=>$e->getMessage(),
            'file'=>$e->getFile(),
            'line'=>$e->getLine(),
            'trace'=>$e->getTrace()
        ]
    );
}
?>
