<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 * @Security("is_granted('ROLE_USER')")
 */
class TaskController extends Controller
{
    /**
     * @Route("/", name="task_pending_index", methods="GET")
     */
    public function pending(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findPendingTasks($this->getUser());

        return $this->render('task/pending.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods="GET")
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'subject' => $task->getSubject(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/check", name="task_check", methods="GET")
     */
    public function check(Task $task)
    {
        $this->denyAccessUnlessGranted('CHECK', $task);

        $task->addStudent($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('subject_show', [
            'id' => $task->getSubject()->getId(),
        ]);
    }
}
