<?php
namespace App\Controller;

use App\Entity\Todo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_home")
     * Method({"GET"})
     */
    public function listTodos()
    {
        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findAll();

        if (!$todos) {
            throw $this->createNotFoundException(
                "No todos found!"
            );
        }

        return $this->render('todo/index.html.twig', compact('todos'));
    }
    
    /**
    * @Route("details/{id}", name="view_todo")
    * Method({"GET"})
    */
   public function viewDetails($id)
   {
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->find($id);
       
       return $this->render('todo/details.html.twig', ['todo' => $todo]);
   }
     /**
     * @Route("edit/{id}", name="edit_todo")
     * Method({"GET", "POST"})
     */
    public function editTodo(Request $request, $id)
    {
        $todo = new Todo;

        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->find($id);

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('category', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('description', TextareaType::class, ['required' => false, 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('priority', ChoiceType::class, ['choices' => ['Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'], 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('due_date', DateTimeType::class, ['attr' => ['style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Create Task', 'attr'=> ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $now = new\DateTime('now');
            $todo->setCreateDate($now);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('notice', 'Task Added');

            return $this->redirectToRoute('todo_home');
        }

        return $this->render('todo/edit.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("delete/{id}", name="delete_todo")
     * Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $todo = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($todo);
        $entityManager->flush();

        $reponse = new Response();
        $reponse->send();
    }

    /**
    * @Route("/create", name="todo_create")
    * Method({"GET", "POST"})
    */
    public function createTodo(Request $request)
    {
        $todo = new Todo;
        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('category', TextType::class, ['attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('description', TextareaType::class, ['required' => false, 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('priority', ChoiceType::class, ['choices' => ['Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'], 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('due_date', DateTimeType::class, ['attr' => ['style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Create Task', 'attr'=> ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $now = new\DateTime('now');
            $todo->setCreateDate($now);
                        
            $todo = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            $this->addFlash('notice', 'Task Added');

            return $this->redirectToRoute('todo_home');
        }

        return $this->render('todo/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}