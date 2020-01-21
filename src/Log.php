<?php

declare(strict_types=1);

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;

class LogSample
{
    private static function getCWLSdkParams(): array
    {
        return [
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => getenv("AWS_ACCESS_KEY"),
                'secret' => getenv("AWS_SECRET_ACCESS_KEY"),
            ]
        ];
    }

    static $cwl_client = null;

    public static function getCWLClient(): CloudWatchLogsClient
    {
        if (is_null(static::$cwl_client)) {
            static::$cwl_client = new CloudWatchLogsClient(static::getCWLSdkParams());
        }
        return static::$cwl_client;
    }

    static $monologger = null;

    public static function getMonoLog(): \Monolog\Logger
    {
        if (is_null(static::$monologger)) {
            $cwl_client = static::getCWLClient();
            $handler = new CloudWatch(
                $cwl_client, // cloudwatch log client
                'log-group-php1', // log group name
                'app_sv1', // stream name
                3 // log life time
            );

            $handler->setFormatter(new JsonFormatter());
            static::$monologger = new Logger('cwl_logger');
            static::$monologger->pushHandler($handler);
        }
        return static::$monologger;
    }
}
