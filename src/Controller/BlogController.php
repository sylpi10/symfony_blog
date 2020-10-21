<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\VarDumper\Cloner\Data;
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
       $posts = $repo->findall();

        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
    
    /**
     * @Route("/posts/{id}", name="detail")
     */
    public function detail(Post $post)
    {

        return $this->render('home/detail.html.twig', [
            'post' => $post
        ]);
    }
}
