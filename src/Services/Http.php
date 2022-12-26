<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services;

use GuzzleHttp\Client;
use Lmc\HttpConstants\Header;

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
            'headers' => $this->headers(),
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents(), true) ?: [];
    }

    protected function url(string $uri): string
    {
        return $this->host . '/' . ltrim($uri, '/');
    }

    protected function headers(): array
    {
        return [
            Header::ACCEPT => 'application/json',
            Header::CONTENT_TYPE => 'application/json'
        ];
    }
}
