<?php


namespace App\Controller;


use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/blog")]
class PostController extends AbstractController
{
    public function __construct(protected ManagerRegistry $registry)
    {

    }

    #[Route('/posts', name: 'post_index')]
    public function postIndex() {
        $postRegistery = $this->registry->getRepository(Post::class);
        $posts = $postRegistery->findAll();

        $postIndex = true;
        return $this->render('layout/index.html.twig', [
            "posts" => $posts,
            "postIndex" => $postIndex
        ]);
    }

    #[Route('/create', name: 'post_create')]
    public function createPost(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $p = $this->registry->getManager();
            $p->persist($post);
            $p->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('layout/new.html.twig', [
            "form" => $form
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function getPost($id)
    {
        $postRegistery = $this->registry->getRepository(Post::class);
        $post = $postRegistery->find($id);

        return $this->render('layout/post.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/edit/{id}', name: 'post_edit', methods: ['GET', 'POST'])]
    public function editPost(Request $request, Post $post, PostRepository $postRepository)
    {

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('layout/edit.html.twig', [
            "form" => $form
        ]);
    }

    #[Route('/delete_post/{id}', name: 'post_delete')]
    public function deletePost(Post $post)
    {
        $p = $this->registry->getManager();

        $p->remove($post);
        $p->flush();

        return $this->redirectToRoute('home');
    }
}