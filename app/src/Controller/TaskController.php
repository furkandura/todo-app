<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Service\DeveloperService;
use App\Service\TaskDecorateService;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="developer_")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Template("task/all.twig")
     */
    public function all(DeveloperService $developerService): array
    {
        $developers = $developerService->getDevelopers();

        return [
            'developers' => $developers
        ];
    }
    /**
     * @Route("/{id}/assigments", name="assignments")
     * @Template("task/assigments.twig")
     */
    public function assignments(Developer $developer, TaskService $taskService): array
    {
        $tasks = $taskService->getTasksWeekGroupByDeveloper($developer);

        return [
            'developer' => $developer,
            'weeks' => $tasks
        ];
    }
}