<?php

namespace App\Command;

use App\Entity\Developer;
use App\Factory\TaskProviderFactory;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TaskDistribute extends Command
{
    private TaskProviderFactory $taskProviderFactory;
    private EntityManagerInterface $em;
    private TaskService $taskService;

    public function __construct(
        EntityManagerInterface $em,
        TaskProviderFactory $taskProviderFactory,
        TaskService $taskService
    )
    {
        parent::__construct();
        $this->taskProviderFactory = $taskProviderFactory;
        $this->em = $em;
        $this->taskService = $taskService;
    }

    protected function configure()
    {
        $this->setName("task:distribute");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $providerTypes = [
            'first_task',
            'second_task'
        ];

        $taskTypes = [];

        foreach ($providerTypes as $providerType) {
            $provider = $this->taskProviderFactory->create($providerType);
            $taskTypes = [...$taskTypes, ...$provider->fetchTasks()];
        }

        $this->taskService->distribute($taskTypes);

        return Command::SUCCESS;
    }
}
