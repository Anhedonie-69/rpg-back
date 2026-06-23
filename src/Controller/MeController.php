<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MeController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        return $this->json([
            'id'        => $user->getId(),
            'email'     => $user->getEmail(),
            'pseudo'    => $user->getPseudo(),
            'language'  => $user->getLanguage(),
            'roles'     => $user->getRoles(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            'options'   => [
                'keyboardLayout' => $user->getOptions()?->getKeyboardLayout() ?? 'azerty',
                'volumeMaster'   => $user->getOptions()?->getVolumeMaster() ?? 80,
                'volumeMusic'    => $user->getOptions()?->getVolumeMusic() ?? 70,
                'volumeSfx'      => $user->getOptions()?->getVolumeSfx() ?? 100,
                'fullscreen'     => $user->getOptions()?->isFullscreen() ?? false,
                'showFps'        => $user->getOptions()?->isShowFps() ?? false,
                'textSpeed'      => $user->getOptions()?->getTextSpeed() ?? 'normal',
            ]
        ]);
    }
}