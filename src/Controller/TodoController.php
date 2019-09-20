<?php


namespace App\Controller;


use App\Entity\Todo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            die('Submited');
        }
        return $this->render('todo/create.html.twig');
    }

    /**
     * @Route("/todo/edit/{id}", name="edit" )
     */
    public function editAction($id, Request $request)
    {
        return $this->render('todo/edit.html.twig');
    }

    /**
     * @Route("/todo/detail/{id}", name="detail")
     */
    public function detailAction($id)
    {
        return $this->render('todo/detail.html.twig');
    }


}