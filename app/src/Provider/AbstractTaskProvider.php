<?php

namespace App\Provider;

use App\Type\TaskType;
use Symfony\Component\HttpClient\HttpClient;

abstract class AbstractTaskProvider
{
    private string $apiUrl;
    protected function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }


    /**
     * @return TaskType[]
     */
    protected function request(string $method, string $endpoint): array
    {
        $collect = [];

        $providerTasks = HttpClient::create()
            ->request($method, $this->apiUrl . $endpoint)
            ->toArray(false);

        foreach ($providerTasks as $providerTask) {
            $collect[] = $this->map($providerTask);
        }

        return $collect;
    }

    abstract public function fetchTasks(): array;
    abstract protected function map(array $providerTask): TaskType;
}