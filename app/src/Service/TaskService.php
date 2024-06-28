<?php

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Task;
use App\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Faker;

class TaskService
{
    private EntityManagerInterface $em;
    private Faker\Generator $faker;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->faker = Faker\Factory::create();
    }


    public function distribute(array $taskTypes): bool
    {
        $lastWeek = $this->em->getRepository(Task::class)->getLastWeek();
        $this->resetDevelopersCapacity();

        $this->createAndAssign($taskTypes, ++$lastWeek);

        if (count($taskTypes) > 0) {
            return $this->distribute($taskTypes);
        }

        return true;
    }

    private function createAndAssign(array &$taskTypes, int $lastWeek): void
    {
        $existingTaskHashes = $this->em->getRepository(Task::class)->getTaskHashes();
        $developers = $this->em->getRepository(Developer::class)->findAll();

        foreach ($taskTypes as $key => $taskType) {
            if (in_array($taskType->getHash(), $existingTaskHashes)) {
                unset($taskTypes[$key]);
                continue;
            }

            $developerIndex = $key % count($developers);
            /** @var Developer $developer */
            $developer = $developers[$developerIndex];

            $effort = $taskType->getDuration() * $taskType->getDifficulty();

            if ($developer->getWeeklyCapacity() >= $effort) {
                $task = $this->createTaskFromType($taskType);
                $task->setDeveloper($developer);
                $task->setWeek($lastWeek);

                $developer->setWeeklyCapacity($developer->getWeeklyCapacity() - $effort);
                unset($taskTypes[$key]);

                $this->em->persist($task);
                $this->em->flush();
            }
        }

        $this->em->clear();

    }

    private function createTaskFromType(TaskType $taskType): Task
    {
        $task = new Task();
        $task->setName($this->faker->sentence());
        $task->setDifficulty($taskType->getDifficulty());
        $task->setDuration($taskType->getDuration());
        $task->setHash($taskType->getHash());

        return $task;
    }

    public function getTasksWeekGroupByDeveloper(Developer $developer): array
    {
        $collect = [];
        $tasks = $this->em->getRepository(Task::class)->findBy(['developer' => $developer]);

        foreach ($tasks as $task){
            $collect[$task->getWeek()][] = $task;
        }

        return $collect;
    }

    private function resetDevelopersCapacity()
    {
        $this->em->getRepository(Developer::class)->resetDeveloperWeeklyCapacity();
    }

}