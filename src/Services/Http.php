<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Http
{
    protected string $host = 'http://localhost:9000';

    public function __construct(
        protected Client $client = new Client()
    ) {
    }

    public function get(string $uri): array
    {
        return $this->request('get', $uri);
    }

    public function post(string $uri, array $data): array
    {
        return $this->request('post', $uri, $data);
    }

    protected function request(string $method, string $uri, array $data = []): array
    {
        try {
            $response = $this->client->request($method, $this->url($uri), [
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?: [];
        }
        catch (RequestException $e) {
            return match ($e->getResponse()->getStatusCode()) {
                425     => [],
                default => throw $e
            };
        }
    }

    protected function url(string $uri): string
    {
        return $this->host . '/' . ltrim($uri, '/');
    }
}
