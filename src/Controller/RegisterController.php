<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\UserOptions;
use App\Entity\Character;
use App\DataFixtures\CharacterStats;
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

        if (empty($data['email']) || empty($data['password']) || empty($data['pseudo'])) {
            return $this->json(['error' => 'Email, pseudo et mot de passe sont obligatoires'], 400);
        }

        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json(['error' => 'Cet email est déjà utilisé'], 409);
        }

        $existingPseudo = $em->getRepository(User::class)->findOneBy(['pseudo' => $data['pseudo']]);
        if ($existingPseudo) {
            return $this->json(['error' => 'Ce pseudo est déjà pris'], 409);
        }

        // User
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPseudo($data['pseudo']);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);
        if (!empty($data['language'])) {
            $user->setLanguage($data['language']);
        }
        $em->persist($user);

        // Profil
        $profile = new UserProfile();
        $profile->setUser($user);
        $em->persist($profile);

        // Options
        $options = new UserOptions();
        $options->setUser($user);
        $em->persist($options);

        // Personnages
        $classes = ['monk', 'warrior', 'red_mage', 'alchemist'];
        foreach ($classes as $slot => $class) {
            $stats = CharacterStats::getBaseStats($class);

            $character = new Character();
            $character->setUser($user);
            $character->setClass($class);
            $character->setSlot($slot + 1);
            $character->setName($stats['name']);
            $character->setHpMax($stats['hpMax']);
            $character->setMpMax($stats['mpMax']);
            $character->setHpCurrent($stats['hpMax']);
            $character->setMpCurrent($stats['mpMax']);
            $character->setAtk($stats['atk']);
            $character->setDef($stats['def']);
            $character->setMag($stats['mag']);
            $character->setRes($stats['res']);
            $character->setSpd($stats['spd']);
            $character->setLck($stats['lck']);
            $character->setUnlocked($stats['unlocked']);
            $character->setSkills($stats['skills']);

            $em->persist($character);
        }

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