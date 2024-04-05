<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Users;
use App\From\Type\PostType;
use App\Repository\PostRepository;
use App\Services\ToDoServices;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ToDoController extends AbstractController
{
    private $toDoServices;
    private $postRepository;
    public function __construct(ToDoServices $toDoServices, PostRepository $postRepository)
    {
        $this->toDoServices = $toDoServices;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route ("/", name = "index",methods = {"GET","HEAD"})
     */
    public function index(SessionInterface $session, ToDoServices $toDoServices): Response
    {
        //$username = $session->get('username');
        $msg = $toDoServices->printMsg();
        $email = $this->getParameter('app.email');

        $post = "Hello";
        if (!$post) {
            throw new \Exception("Empty Post");
        } else {
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
        //$entityManager = $this->getDoctrine()->getManager();

        $posts = $this->postRepository->findAll();

        $number = random_int(0, 100);
        $requestparams = $request->attributes->all();

        return $this->render('first.html.twig', [
            'number' => $number,
            'params' => $requestparams,
            'posts' => $posts
        ]);
    }
    /**
     * @Route("/addpost",name = "add_post",methods = {"POST","GET"})
     */
    public function addPost(Request $request): Response //, ValidatorInterface $validatorInterface
    {
        //Used for communicating with databse.
        // $entityManager = $this->getDoctrine()->getManager();

        //Creating new Post by hard-coring values.
        $post = new Post();
        //$post->setTitle('New post');
        // $post->setDescription('This is a new post');

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // echo "Form submitted";
            $post = $form->getData();

            $this->postRepository->add($post);

            return $this->render($this->generateUrl('postlist'));
            // return new Response("<html><body><h1>Post added</h1></body></html>");
        }


        /*
        $form = $this->createFormBuilder($post)
        ->add('title',TextType::class)
        ->add('description',TextType::class)
        ->add('add',SubmitType::class,['lable' => 'Add Post'])
        ->getForm();
        */
        //Validate Object before adding into table
        // $err = $validatorInterface->validate($post);

        // if(count($err) > 0){
        //     return new HttpException(400,'Object could not be added, please provide a valid information.');
        // }
        //using PostRepository service call add method
        // $this->postRepository->add($post);

        // $allpost = $this->generateUrl('postlist');

        return $this->render('add-post.html.twig', [
            'form' => $form->createView()
        ]);

        // return new Response(
        //     "<html><body><h1>Add Post"."<a href = >"."Link</a>"."</h1></body></html>"
        // );
    }

    /**
     * @Route("/get-post/{id}",name = "show_post", requirements = {"id" = "\d+"} , defaults = {"name" = "devansh"})
     */
    public function get_post(int $id = 1, string $name, Request $request)
    {

        //To get Query parameters
        // $userid = $request->query->get('id');

        $eventManager = $this->getDoctrine()->getManager();

        $post = $eventManager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException("No Post Found");
        }

        //Render a post template

        return new Response(
            "<html><body><h1>Post-" . $post->getTitle() . "</h1></body></html>"
        );
    }

    /**
     * @Route("/find-post-title/{title}")
     */
    public function findPostByTitle(string $title)
    {

        $post = $this->postRepository->findByTitleField($title);

        if (!$post) {
            throw $this->createNotFoundException("No Post With Title: " . $title);
        }
        return new Response(
            "<html><body><p>Post Title-" . $post[0]->getTitle() . "</p><p>Post Description-" . $post[0]->getDescription() . "</p></body></html>"
        );
    }

    /**
     * @Route("update-post/{id}", name="update-post")
     */
    public function update_post(int $id)
    {
        $eventManager = $this->getDoctrine()->getManager();

        $post = $eventManager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException("No Object is found");
        }
        $post->setTitle("New Title");

        $eventManager->flush();

        $seePost = $this->generateUrl("show_post", ["id" => $id]);

        return new Response(
            "<html><body><h1>Add Post" . "<a href = $seePost>" . "Link</a>" . "</h1></body></html>"
        );
    }

    /**
     * @Route("/getPostByUser/{userid}", name="getPostByUser")
     */
    public function getPostByUser(int $userid, LoggerInterface $logger): Response
    {
        $post = $this->postRepository->findByUserId($userid);

        $logger->info('We are logging!');
        return new Response(
            "<html><body><h5>" . $post[0]->getTitle() . "</h5><h5>" . $post[1]->getTitle() . "</h5></body></html>"
        );
        // ...
    }

    /**
     * @Route("/user", name = "user_manager")
     */
    public function userInfo(Request $request)
    {
        //SessionInterface $session in method parameters
        // $session->set('username', 'devansh');
        // $session->set('password', 'hello');

        // $indexpage = $this->generateUrl("index");

        //way to return json response
        //return $this->json(['username'=>'devansh', 'password'=>'test']);
        $user = array('username' => 'devansh', 'password' => 'test');

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
        return $this->render('user-info.html.twig', [
            'username' => $user['username'],
            'posts' => array($post1, $post2, $post3)
        ]);
    }
    /**
     * @Route("/addPostByUser",name="addPostByUser")
     */
    public function addPostByUser()
    {
        $post = new Post();
        $post->setTitle('Post by user');
        $post->setDescription('This is first post by user');

        $user = new Users();
        $user->setEmail('test@test.com');
        $user->setUsername('test');

        $post->setUsers($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response("Product and user added");
    }
}
