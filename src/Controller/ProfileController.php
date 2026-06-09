<?php
namespace App\Controller;

use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    // GET /api/profile → récupère le profil de l'utilisateur connecté
    #[Route('/api/profile', name: 'api_profile_get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        return $this->json([
            'email'     => $user->getEmail(),
            'pseudo'    => $user->getPseudo(),
            'language'  => $user->getLanguage(),
            'avatar'    => $profile?->getAvatar(),
            'bio'       => $profile?->getBio(),
            'birthDate' => $profile?->getBirthDate()?->format('Y-m-d'),
            'country'   => $profile?->getCountry(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    // PUT /api/profile → modifie le profil
    #[Route('/api/profile', name: 'api_profile_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        // Mise à jour des champs User
        if (!empty($data['pseudo']))   $user->setPseudo($data['pseudo']);
        if (!empty($data['language'])) $user->setLanguage($data['language']);

        // Mise à jour du profil
        $profile = $user->getProfile();

        // Crée le profil s'il n'existe pas (sécurité)
        if (!$profile) {
            $profile = new UserProfile();
            $profile->setUser($user);
            $em->persist($profile);
        }

        if (array_key_exists('avatar', $data))    $profile->setAvatar($data['avatar']);
        if (array_key_exists('bio', $data))        $profile->setBio($data['bio']);
        if (array_key_exists('country', $data))    $profile->setCountry($data['country']);
        if (array_key_exists('birthDate', $data)) {
            $profile->setBirthDate(
                $data['birthDate'] ? new \DateTime($data['birthDate']) : null
            );
        }

        $em->flush();

        return $this->json(['message' => 'Profil mis à jour avec succès']);
    }
}