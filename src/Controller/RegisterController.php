<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\UserOptions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Vérification des champs obligatoires
        if (empty($data['email']) || empty($data['password']) || empty($data['pseudo'])) {
            return $this->json([
                'error' => 'Email, pseudo et mot de passe sont obligatoires'
            ], 400);
        }

        // Vérifie si l'email existe déjà
        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json(['error' => 'Cet email est déjà utilisé'], 409);
        }

        // Vérifie si le pseudo existe déjà
        $existingPseudo = $em->getRepository(User::class)->findOneBy(['pseudo' => $data['pseudo']]);
        if ($existingPseudo) {
            return $this->json(['error' => 'Ce pseudo est déjà pris'], 409);
        }

        // Création de l'utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPseudo($data['pseudo']);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        // Language optionnel
        if (!empty($data['language'])) {
            $user->setLanguage($data['language']);
        }

        $em->persist($user);
        
        $profile = new UserProfile();
        $profile->setUser($user);
        $em->persist($profile);

        $options = new UserOptions();
        $options->setUser($user);
        $em->persist($options);

        $em->flush();

        return $this->json([
            'message' => 'Compte créé avec succès',
            'user' => [
                'id'        => $user->getId(),
                'email'     => $user->getEmail(),
                'pseudo'    => $user->getPseudo(),
                'language'  => $user->getLanguage(),
                'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
}