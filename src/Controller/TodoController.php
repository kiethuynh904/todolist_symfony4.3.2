<?php


namespace App\Controller;


use App\Entity\Todo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    /**
     * @Route("/todo/create", name="create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createFormBuilder($todo)
            ->add('name',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('category',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('description',TextareaType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('priority',ChoiceType::class,array(
                'attr'=>array('class'=> 'form-control'),
                'choices'=>array('Low' => 'Low','Normal'=>'Normal','High'=>'High')
            ))
            ->add('due_date',DateTimeType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('save',SubmitType::class,array(
                'label'=>'Create Todo',
                'attr'=>array('class'=> 'btn btn-success')
            ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
//            die('Submited');
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
            $em = $this->getDoctrine()->getManager();

            $em->persist($todo);

            $em->flush();
            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todolist');
        }
        return $this->render('todo/create.html.twig',array(
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
        $form = $this->createFormBuilder($todo)
            ->add('name',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('category',TextType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('description',TextareaType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('priority',ChoiceType::class,array(
                'attr'=>array('class'=> 'form-control'),
                'choices'=>array('Low' => 'Low','Normal'=>'Normal','High'=>'High')
            ))
            ->add('due_date',DateTimeType::class,array(
                'attr'=>array('class'=> 'form-control')
            ))
            ->add('save',SubmitType::class,array(
                'label'=>'Create Todo',
                'attr'=>array('class'=> 'btn btn-success')
            ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
//            GET VALUE
            $em = $this->getDoctrine()->getManager();
            $todo =$em->getRepository(Todo::class)->find($id);
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
//          SET VALUE
            $now = new \DateTime('now');
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);


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
            'form'=>$form->createView()
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
        $todo =$em->getRepository(Todo::class)->find($id);
        $em->remove($todo);
        $em->flush();
        $this->addFlash(
            'notice',
            'Todo Remove'
        );
        return $this->redirectToRoute('todolist');
    }


}