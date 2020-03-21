<?php

declare(strict_types=1);

namespace App\Command;

use App\Amqp\Lib\BaseConsumer;
use App\Command\Lib\ProcessCommand;
use Hyperf\Amqp\Consumer;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Process\AbstractProcess;
use Psr\Container\ContainerInterface;
use Swoole\Process;
use Swoole\Timer;
use Symfony\Component\Console\Input\InputArgument;
use xingwenge\canal_php\CanalConnectorFactory;
use xingwenge\canal_php\CanalClient;
use xingwenge\canal_php\Fmt;
use Hyperf\DbConnection\Db;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Amqp\Annotation\Consumer as AnnotationConsumer;

/**
 * @Command
 */
class RabbitMQCommand extends ProcessCommand
{

    /**
     * @var ContainerInterface
     */
    protected $container;
    protected $coroutine = false;

    protected $masterName = 'rabbitMQ';
    protected $baseConsumerPath = 'App\Amqp\Consumer\\';

    protected $consumerAnnotation;
    protected $consumer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
        $this->setName('rabbit:process');
    }

    public function configure()
    {
        $this->setDescription('rabbit自定义进程');
        $this->addArgument('rabbit', InputArgument::REQUIRED, 'rabbit consumer类名');
    }

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $this->masterName = $rabbit = $this->input->getArgument('rabbit');
        if (!class_exists($rabbit)) {
            $rabbit = $this->baseConsumerPath . $rabbit;
            if (!class_exists($rabbit)) {
                throw new \Exception('错误的consumer');
            }
        }

        if(!$this->consumerAnnotation = AnnotationCollector::getClassAnnotation($rabbit, AnnotationConsumer::class)){
            throw new \Exception('错误的consumer!');
        }

        $this->consumer = new $rabbit();
        if (!$this->consumer instanceof BaseConsumer) {
            throw new \Exception('consumer必须继承BaseConsumer', 0);
        }
        if ($this->consumerAnnotation->exchange) {
            $this->consumer->setExchange($this->consumerAnnotation->exchange);
        }
        if ($this->consumerAnnotation->routingKey) {
            $this->consumer->setRoutingKey($this->consumerAnnotation->routingKey);
        }
        if ($this->consumerAnnotation->queue) {
            $this->consumer->setQueue($this->consumerAnnotation->queue);
        }
        $this->nums = $this->consumerAnnotation->nums?: 1;
    }

    protected function runProcess()
    {
        $this->container->get(Consumer::class)->consume($this->consumer);
    }



}
