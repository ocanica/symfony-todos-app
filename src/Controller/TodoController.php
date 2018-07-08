<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listTodos()
    {
        return $this->render('todo/index.html.twig', ['test' => 'list']);
    }

     /**
     * @Route("/create", name="todo_create")
     */
    public function createTodo()
    {
        return $this->render('todo/create.html.twig', ['test' => 'create']);
    }
    
     /**
     * @Route("edit/{id}", name="todo_edit")
     */
    public function editTodo($id)
    {
        return $this->render('todo/edit.html.twig', ['test' => 'edit', 'id' => $id]);
    }
    
     /**
     * @Route("details/{id}", name="todo_details")
     */
    public function listDetails($id)
    {
        return $this->render('todo/details.html.twig', ['test' => 'edit', 'id' => $id]);
    }
}