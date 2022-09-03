<?php

declare(strict_types=1);

namespace App\Subscriber;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiLogSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $apiLogger)
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $this->log($event->getThrowable());
    }

    private function log(\Throwable $throwable)
    {
        $log = [
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'called' => [
                'file' => $throwable->getTrace()[0]['file'],
                'line' => $throwable->getTrace()[0]['line'],
            ],
            'occurred' => [
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ],
        ];

        if ($throwable->getPrevious() instanceof Exception) {
            $log += [
                'previous' => [
                    'message' => $throwable->getPrevious()->getMessage(),
                    'exception' => get_class($throwable->getPrevious()),
                    'file' => $throwable->getPrevious()->getFile(),
                    'line' => $throwable->getPrevious()->getLine(),
                ],
            ];
        }

        $this->apiLogger->error(json_encode($log));
    }

}