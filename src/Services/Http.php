<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use GuzzleHttp\Client;

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
        $response = $this->client->request($method, $this->url($uri), [
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true) ?: [];
    }

    protected function url(string $uri): string
    {
        return $this->host . '/' . ltrim($uri, '/');
    }
}
