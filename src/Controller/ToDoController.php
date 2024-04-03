<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route("/get-all-post",name = "post_list" , methods = {"GET","HEAD"}, defaults = {})
     */
    public function get_todo_list(Request $request)
    {
        $number = random_int(0, 100);
        $requestparams = $request->attributes->all();

        return $this->render('first.html.twig', [
            'number' => $number,    
            'params' => $requestparams
        ]);
    }

    /**
    * @Route("/add-post",name = "add_post", condition = "context.getMethod() in ['GET']")
    */
    public function add_todo_post(){

        $allpost = $this->generateUrl('post_list');

        return new Response(
            "<html><body><h1>Add Post"."<a href = $allpost>"."Link</a>"."</h1></body></html>"
        );
    }

    /**
     * @Route("/get-post/{id}",name = "edit_post", requirements = {"id" = "\d+"} , defaults = {"name" = "devansh"})
     */
    public function get_post(int $id = 1,string $name){
        return new Response(
            "<html><body><h1>Post-".$id."</h1></body></html>"
        );
    }
}
?>