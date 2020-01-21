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
try {
    throw new \Exception("Woooo I am Exception!!");
} catch (\Exception $e) {
    $monolog = \LogSample::getMonoLog();
    $monolog->error(
        "I got exception",
        [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace()
        ]
    );
}
?>
<br>
... and make some error.<br>
<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $monolog = \LogSample::getMonoLog();
    $monolog->error(
        "I got error",
        [
            'errstr' => $errstr,
            'errno' => $errno,
            'errfile' => $errfile,
            'errline' => $errline
        ]
    );
});

echo $un_exists_var;
?>

... but could not send fatal log!
<?php

// うまくいきません！
register_shutdown_function(
    function () {
        $e = error_get_last();
        if (
            $e['type'] == E_ERROR ||
            $e['type'] == E_PARSE ||
            $e['type'] == E_CORE_ERROR ||
            $e['type'] == E_COMPILE_ERROR ||
            $e['type'] == E_USER_ERROR
        ) {

            $monolog = \LogSample::getMonoLog();
            $monolog->error(
                "I got error",
                [
                    'errstr' => $e['message'],
                    'errtype' => $e['type'],
                    'errfile' => $e['file'],
                    'errline' => $$e['line']
                ]
            );
        }
    }
);

// これを呼ぶと、FATALでエラーがとびません！
// call_un_existed_funciton_cause_fatal();
