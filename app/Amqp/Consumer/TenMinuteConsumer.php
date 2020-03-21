<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Amqp\Lib\DelayConsumer;
use App\Util\DelayQueue;
use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;


/**
 * @Consumer(nums=1,enable=false)
 */
class TenMinuteConsumer extends DelayConsumer
{
    protected $delayQueue = DelayQueue::TEN_MINUTE_TIMEOUT;

//    protected $enable = false;

    public function consume($data): string
    {
        if(DelayQueue::loopDelay($data)) {
            return Result::ACK;
        }else{
            return Result::NACK;
        }
    }


}