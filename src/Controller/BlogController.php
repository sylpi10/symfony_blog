<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repo, PaginatorInterface $paginator,Request $request): Response
    {
        // $posts = $repo->getPostsAndComs();
        
        $posts = $paginator->paginate(
            $repo->getPostsAndComsPaginated(),
            $request->query->getInt('page', 1),
            6
        );
      
        return $this->render('blog/index.html.twig', [
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
        return $this->render('blog/detail.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/publish-article", name="publish")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, 
    EntityManagerInterface $manager, UploaderInterface $upload): Response  
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            "validation_groups" => ["Default", "create"]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var UploadedFile $file */
            $file = $form->get('file')->getData();
        
            $post->setImage($upload->upload($file));
          
            $manager->persist($post);
            $manager->flush();
            //without redirection, com will be re-posted on each reload (f5) 
            return $this->redirectToRoute("detail", ["id" => $post->getId()]);
        }
        return $this->render("blog/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/update-article/{id}", name="update")
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function update(Request $request,
     EntityManagerInterface $manager, Post $post, UploaderInterface $upload): Response  
    {
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             /**@var UploadedFile $file */
             $file = $form->get('file')->getData();

             if ($file != null) {
                $post->setImage($upload->upload($file));
            }
          
            $manager->flush();
            //without redirection, com will be re-posted on each reload (f5) 
            return $this->redirectToRoute("detail", ["id" => $post->getId()]);
        }
        return $this->render("blog/update.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
