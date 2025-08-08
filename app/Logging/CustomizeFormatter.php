<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Handler\RotatingFileHandler;

class CustomizeFormatter
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new \Monolog\Formatter\JsonFormatter());
        }
    }
}
