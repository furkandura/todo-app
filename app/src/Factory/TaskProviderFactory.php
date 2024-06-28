<?php

namespace App\Factory;

use App\Provider\AbstractTaskProvider;
use App\Provider\FirstTaskProvider;
use App\Provider\SecondTaskProvider;
use InvalidArgumentException;

class TaskProviderFactory
{
    public function create(string $providerType): AbstractTaskProvider
    {
        switch ($providerType) {
            case 'first_task':
                return new FirstTaskProvider();
            case 'second_task':
                return new SecondTaskProvider();
            default:
                throw new InvalidArgumentException("Unknown provider type: $providerType");
        }
    }
}