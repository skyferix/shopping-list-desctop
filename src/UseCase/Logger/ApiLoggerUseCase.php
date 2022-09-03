<?php

declare(strict_types=1);

namespace App\UseCase\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiLoggerUseCase
{
    public function __construct(private LoggerInterface $apiLogger)
    {
    }

    public function support($exception): bool
    {
        return $exception instanceof TransportExceptionInterface;
    }

    public function log(string $method, string $url, ResponseInterface $response): void
    {
        $logType = $this->getLogType($response->getStatusCode());
        $logBody = $this->getLogBody($method, $url, $response);

        $this->apiLogger->{$logType}($logBody);
    }

    private function getLogType(int $statusCode): string
    {
        if (Response::HTTP_OK === $statusCode) {
            return 'info';
        }

        return 'error';
    }

    protected function getLogBody(string $method, string $url, ResponseInterface $response): string
    {
        $body = [
            'method' => $method,
            'url' => $url,
            'code' => $response->getStatusCode(),
            'content' => substr($response->getContent(false), 0, 100),
            'headers' => $response->getHeaders(false),
        ];

        return json_encode($body);
    }
}