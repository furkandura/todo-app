<?php

namespace App\Provider;

use App\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;

class SecondTaskProvider extends AbstractTaskProvider
{
    public function __construct()
    {
        $this->setApiUrl("https://run.mocky.io/v3/");
    }

    public function fetchTasks(): array
    {
        return $this->request(Request::METHOD_GET, '2cf6bfb2-2dd4-44ff-9978-0b57f861301f');
    }

    protected function map(array $providerTask): TaskType
    {
        return (new TaskType())
            ->setId($providerTask['id'])
            ->setDuration($providerTask['value'])
            ->setDifficulty($providerTask['estimated_duration'])
            ->setHash(md5($providerTask['id'] . get_class()));
    }
}