<?php

declare(strict_types=1);

namespace App\Request;

use App\UseCase\Logger\ApiLoggerUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiRequest
{
    const LOGIN_URL = '/login';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    private ResponseInterface $response;
    private array $headers;
    private mixed $content;
    private int $statusCode;
    private ?string $token = null;

    public function __construct(
        private string              $apiBaseUrl,
        private HttpClientInterface $client,
        private ApiLoggerUseCase    $loggerUseCase,
        private bool                $debug
    ) {
    }

    public function login(array $data = []): ApiRequest
    {
        $this->basicRequest(self::METHOD_POST, self::LOGIN_URL, $data);
        $token = $this->response->getContent(false);

        if (Response::HTTP_OK === $this->getStatusCode()) {
            $this->token = json_decode($token ?? '')?->token;
        }

        return $this;
    }

    public function request(string $token, string $method, string $relativeUrl, array $data = [], array $options = []): ApiRequest
    {
        $options['auth_bearer'] = $token;

        $this->basicRequest($method, $relativeUrl, $data, $options);

        return $this;
    }

    public function basicRequest(string $method, string $relativeUrl, array $data = [], array $options = []): ApiRequest
    {
        $url = $this->apiBaseUrl . $relativeUrl;
        $data = array_merge(['json' => $data], $options);

        $this->response = $this->client->request($method, $url, $data);

        $this->loggerUseCase->log($method, $url, $this->response);

        $this->statusCode = $this->response->getStatusCode();
        $this->content = $this->response->getContent($this->debug);
        $this->headers = $this->response->getHeaders($this->debug);

        return $this;
    }

    public function getToken(): string|null
    {
        return $this->token;
    }

    public function getPureResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getContent(bool $assoc = false)
    {
        return json_decode($this->content, $assoc);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function generateError(): string
    {
        return $this->generateErrorBasedOnStatusCode($this->statusCode);
    }

    public function generateErrorBasedOnStatusCode(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'error.400',
            401 => 'error.401',
            default => 'error.500',
        };
    }

    public function hasNoAuth(): bool
    {
        return 401 === $this->statusCode;
    }

    public function isSuccess(): bool
    {
        return 2 === $this->getFirstDigit($this->statusCode);
    }

    public function notFound(): bool
    {
        return 404 === $this->statusCode;
    }

    private function getFirstDigit(int $number): int
    {
        while ($number > 9) {
            $number /= 10;
        }

        return (int)$number;
    }
}