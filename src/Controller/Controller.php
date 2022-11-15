<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class Controller extends AbstractController
{
    public function __construct(protected ManagerRegistry $registry){

    }
    #[Route('', name: 'home')]
    public function index(): Response
    {
        $postRegistery = $this->registry->getRepository(Post::class);
        $posts = $postRegistery->findA();

        return $this->render('layout/index.html.twig', [
            "posts" => $posts
        ]);
    }
}
