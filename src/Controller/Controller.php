<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/")]
class Controller extends AbstractController
{
    public function __construct(protected ManagerRegistry $registry, protected UserPasswordHasherInterface $passwordEncoder){

    }

    #[Route('', name: 'home')]
    public function index(): Response
    {
        $postRegistery = $this->registry->getRepository(Post::class);
        $posts = $postRegistery->findPostsWithLimit(3);

        return $this->render('layout/index.html.twig', [
            "posts" => $posts
        ]);
    }

    #[Route('/user', name: 'new_user')]
    public function createUser(UserRepository $userRepository) {
        $user = new User();

        $user->setEmail('user@email.com');
        $userPassword = 'mdp1234!';
        $hashPassword = $this->passwordEncoder->hashPassword($user, $userPassword);
        $user->setPassword($hashPassword);
        $user->setRoles(['ROLE_USER']);

        $userRepository->save($user, true);

        return $this->redirectToRoute('home');
    }

}
