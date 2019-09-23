<?php


namespace App\Controller;


use App\Entity\Todo;
use App\Form\formtype;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{

    /**
     * @Route("/todo", name="todolist" ,methods={"GET","HEAD"})
     */
    public function listAction()
    {
        $todoList = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findAll();
//        dump($todoList);
//        die();
        return $this->render('todo/index.html.twig', array(
            'todoList' => $todoList
        ));
    }
    public function createMyForm($todo, $form)
    {
        $name = $form['name']->getData();
        $category = $form['category']->getData();
        $description = $form['description']->getData();
        $priority = $form['priority']->getData();
        $due_date = $form['due_date']->getData();
        $now = new \DateTime('now');
        $todo->setName($name);
        $todo->setCategory($category);
        $todo->setDescription($description);
        $todo->setPriority($priority);
        $todo->setDueDate($due_date);
        $todo->setCreateDate($now);
    }

    /**
     * @Route("/todo/create", name="create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm(formtype::class, $todo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->createMyForm($todo,$form);

            $em = $this->getDoctrine()->getManager();

            $em->persist($todo);

            $em->flush();
            $this->addFlash(
                'notice',
                'added'
            );
            return $this->redirectToRoute('todolist');
        }
        return $this->render('todo/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/edit/{id}", name="edit" )
     */
    public function editAction($id, Request $request)
    {
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->find($id);
        $now = new \DateTime('now');
        $todo->setName($todo->getName());
        $todo->setCategory($todo->getCategory());
        $todo->setDescription($todo->getDescription());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $todo->setCreateDate($todo->getCreateDate());
        $form = $this->createForm(formtype::class, $todo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            GET VALUE
            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository(Todo::class)->find($id);
            $this->createMyForm($todo,$form);
            $em->persist($todo);

            $em->flush();
            $this->addFlash(
                'notice',
                'Todo Updated'
            );
            return $this->redirectToRoute('todolist');
        }
        return $this->render('todo/edit.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/detail/{id}", name="detail")
     */
    public function detailAction($id)
    {
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->find($id);
//        dump($todoList);
//        die();
        return $this->render('todo/detail.html.twig', array(
            'todo' => $todo
        ));
    }

    /**
     * @Route("/todo/delete/{id}", name="delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository(Todo::class)->find($id);
        $em->remove($todo);
        $em->flush();
        $this->addFlash(
            'notice',
            'Todo Remove'
        );
        return $this->redirectToRoute('todolist');
    }
}