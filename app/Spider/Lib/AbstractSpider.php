<?php

namespace App\Spider\Lib;


use App\Util\Logger;

abstract class AbstractSpider implements Spider
{
    protected static function log($message, $context = [])
    {
        echo $message,PHP_EOL;
        Logger::get(SPIDER_LOG, SPIDER_LOG)->info($message, $context);
    }
}