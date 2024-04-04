<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\ToDoServices;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


class ToDoController extends AbstractController
{
    private $toDoServices;
    private $postRepository;
    public function __construct(ToDoServices $toDoServices, PostRepository $postRepository){
        $this->toDoServices = $toDoServices;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route ("/", name = "index",methods = {"GET","HEAD"})
     */
    public function index(SessionInterface $session, ToDoServices $toDoServices):Response{
        //$username = $session->get('username');
        $msg = $toDoServices->printMsg();
        $email = $this->getParameter('app.email');

        $post = "Hello";
        if(!$post){
            throw new \Exception("Empty Post") ;
        }else{
            return new Response(
                $msg . $email
            );
        }
    }


    /**
     * @Route("/get-all-post",name = "postlist" , methods = {"GET","HEAD"}, defaults = {})
     */
    public function getAllPost(Request $request)
    {
        // $entityManager = $this->getDoctrine()->getManager();

        // $posts = $entityManager->find


        $number = random_int(0, 100);
        $requestparams = $request->attributes->all();

        return $this->render('first.html.twig', [
            'number' => $number,    
            'params' => $requestparams
        ]);
    }
    /**
     * @Route("/addpost",name = "add_post",methods = {"GET"})
     */
    public function addPost() : Response{

        //Used for communicating with databse.
        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle('First Post');
        $post->setDescription('This is my first post');

        $entityManager->persist( $post );

        $entityManager->flush();
        
        // $this->postRepository->save($post);

        $allpost = $this->generateUrl('post_list');

        // return $this->render('add-post.html.twig');//,['allpostlinks' => $allpost]);
        
        return new Response(
            "<html><body><h1>Add Post"."<a href = $allpost>"."Link</a>"."</h1></body></html>"
        );
    }

    /**
     * @Route("/get-post/{id}",name = "edit_post", requirements = {"id" = "\d+"} , defaults = {"name" = "devansh"})
     */
    public function get_post(int $id = 1,string $name,Request $request){
        $userid = $request->query->get('id');
        return new Response(
            "<html><body><h1>Post-".$userid."</h1></body></html>"
        );
    }
    /**
    * @Route("/lucky/number/{max}")
    */
    public function number(int $max, LoggerInterface $logger): Response
    {
        $logger->info('We are logging!');
        return new Response(
            "<html><body><h1>Logging in console</h1></body></html>"
        );
    // ...
    }
    /**
     * @Route("/user", name = "user_manager")
     */
    public function userInfo(Request $request){
        //SessionInterface $session in method parameters
        // $session->set('username', 'devansh');
        // $session->set('password', 'hello');

        // $indexpage = $this->generateUrl("index");

        //way to return json response
        //return $this->json(['username'=>'devansh', 'password'=>'test']);
        $user = array('username'=>'devansh', 'password'=>'test');

        $post1 = (object) [
            'name' => 'post1',
            'details' => 'This is post-1'
        ];
        $post2 = (object) [
            'name' => 'post2',
            'details' => 'This is post-2'
        ];
        $post3 = (object) [
            'name' => 'post3',
            'details' => 'This is post-3'
        ];
        
        //way to return json response.
        //return new Response(json_encode(['username'=>'devansh', 'password'=>'test']));
        return $this->render('user-info.html.twig',[
            'username' => $user['username'],
            'posts' => array($post1,$post2,$post3)
        ]);
    }
}
