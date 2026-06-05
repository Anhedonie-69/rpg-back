<?php
namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    // GET /api/news → liste toutes les news (public)
    #[Route('/api/news', name: 'api_news_list', methods: ['GET'])]
    public function list(NewsRepository $repo): JsonResponse
    {
        $news = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->json(array_map(fn($n) => [
            'id'        => $n->getId(),
            'title'     => $n->getTitle(),
            'content'   => $n->getContent(),
            'author'    => $n->getAuthor(),
            'createdAt' => $n->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $n->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ], $news));
    }

    // GET /api/news/{id} → une news (public)
    #[Route('/api/news/{id}', name: 'api_news_show', methods: ['GET'])]
    public function show(News $news): JsonResponse
    {
        return $this->json([
            'id'        => $news->getId(),
            'title'     => $news->getTitle(),
            'content'   => $news->getContent(),
            'author'    => $news->getAuthor(),
            'createdAt' => $news->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $news->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    // POST /api/admin/news → créer une news (ROLE_ADMIN)
    #[Route('/api/admin/news', name: 'api_admin_news_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);

        if (empty($data['title']) || empty($data['content'])) {
            return $this->json(['error' => 'Titre et contenu obligatoires'], 400);
        }

        $news = new News();
        $news->setTitle($data['title']);
        $news->setContent($data['content']);
        
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();
        $news->setAuthor($data['author'] ?? $currentUser->getPseudo());

        $em->persist($news);
        $em->flush();

        return $this->json([
            'message' => 'News créée avec succès',
            'id'      => $news->getId(),
        ], 201);
    }

    // PUT /api/admin/news/{id} → modifier une news (ROLE_ADMIN)
    #[Route('/api/admin/news/{id}', name: 'api_admin_news_update', methods: ['PUT'])]
    public function update(News $news, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);

        if (!empty($data['title']))   $news->setTitle($data['title']);
        if (!empty($data['content'])) $news->setContent($data['content']);
        if (!empty($data['author']))  $news->setAuthor($data['author']);

        $em->flush();

        return $this->json(['message' => 'News modifiée avec succès']);
    }

    // DELETE /api/admin/news/{id} → supprimer une news (ROLE_ADMIN)
    #[Route('/api/admin/news/{id}', name: 'api_admin_news_delete', methods: ['DELETE'])]
    public function delete(News $news, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($news);
        $em->flush();

        return $this->json(['message' => 'News supprimée avec succès']);
    }
}