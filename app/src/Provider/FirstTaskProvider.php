<?php

namespace App\Provider;

use App\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;

class FirstTaskProvider extends AbstractTaskProvider
{
    public function __construct()
    {
        $this->setApiUrl("https://run.mocky.io/v3/");
    }

    public function fetchTasks(): array
    {
        return $this->request(Request::METHOD_GET, 'eee951d9-0976-422f-8e84-ea39e8ea277f');
    }

    protected function map(array $providerTask): TaskType
    {
        return (new TaskType())
            ->setId($providerTask['id'])
            ->setDuration($providerTask['sure'])
            ->setDifficulty($providerTask['zorluk'])
            ->setHash(md5($providerTask['id'].get_class()))
        ;
    }
}