<?php

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Task;
use App\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Faker;

class DeveloperService
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @return Developer[]
     */
    public function getDevelopers(): array
    {
        return $this->em->getRepository(Developer::class)->findAll();
    }

}