<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Manager\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class TaskController extends AbstractController
{
    private $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    #[Route('/tasks', name: 'task_todo_list')]
    public function listAction(): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->taskManager->handleListAction(),
            ]
        );
    }

    #[Route('/tasks/done', name: 'task_done_list')]
    public function doneListAction(): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->taskManager->handleListAction(true),
            'type' => 'done',
            ]
        );
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskManager->handleCreateOrUpdate($task);
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_todo_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskManager->handleCreateOrUpdate();
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_todo_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task): Response
    {
        $task = $this->taskManager->handleToggleAction($task);
        $status = (true === $task->isIsDone()) ? 'faite' : 'non terminée';
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme '.$status, $task->getTitle()));

        return $this->redirectToRoute('task_todo_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    #[isGranted("TASK_DELETE", subject:"task", statusCode:401)]
    public function deleteTaskAction(Task $task): Response
    {
        $this->taskManager->handleDeleteAction($task);
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_todo_list');
    }
}