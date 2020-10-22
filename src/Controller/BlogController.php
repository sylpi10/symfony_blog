<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\EventManager\EventManagerInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repo): Response
    {
        $posts = $repo->getPostsAndComs();
      
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            
        ]);
    }
    
    /**
     * @Route("/posts/{id}", name="detail")
     */
    public function detail(Post $post, Request $request, EntityManagerInterface $manager)
    {

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setPostedAt(new \DateTime);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            //without redirection, com will be re-posted on each reload (f5) 
            return $this->redirectToRoute("detail", ["id" => $post->getId()]);
        }
        return $this->render('home/detail.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
