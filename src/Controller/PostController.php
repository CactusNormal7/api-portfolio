<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends AbstractController
{
    #[Route('/caca/posts', name: 'get_all_posts', methods: ['GET'])]
    public function getAllPosts(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/caca/posts', name: 'create_post', methods: ['POST'])]
    public function createPost(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setSlug($data['slug']); // Ajouter le slug
        $post->setImage($data['image']); // Ajouter l'image
        $post->setFullContent($data['full_content']); // Ajouter le full_content
        $post->setCreatedAt(new \DateTime());

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post, Response::HTTP_CREATED);
    }


}
