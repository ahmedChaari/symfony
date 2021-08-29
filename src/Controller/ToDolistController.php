<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ToDolistController extends AbstractController
{
    /**
     * @Route("/to", name="to_dolist")
     */
    public function index(): Response
    {
       $tasks = $this->getDoctrine()->getRepository(Task::class)
                     ->findBy([],['id'=>'DESC']);
        return $this->render('index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/create", name="create_task", methods="POST")
     */
    public function create(Request $request)
    {
        $title = trim($request->request->get('title'));
        
        if (empty($title)) {
           return $this->redirectToRoute('to_dolist');
        }
        $entityManager = $this->getDoctrine()->getManager();
        


        $task = new Task;
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_dolist');



    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());
        $entityManager->flush();

        return $this->redirectToRoute('to_dolist');
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     */

    public function delete(Task $id)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('to_dolist');
    }

}
