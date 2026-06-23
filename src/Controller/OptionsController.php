<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\UserOptions;

class OptionsController extends AbstractController
{
    // GET /api/options → récupère les options
    #[Route('/api/options', name: 'api_options_get', methods: ['GET'])]
    public function get(): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $options = $user->getOptions();

        if (!$options) {
            return $this->json(['error' => 'Options introuvables'], 404);
        }

        return $this->json([
            'volumeMaster'   => $options->getVolumeMaster(),
            'volumeMusic'    => $options->getVolumeMusic(),
            'volumeSfx'      => $options->getVolumeSfx(),
            'fullscreen'     => $options->isFullscreen(),
            'showFps'        => $options->isShowFps(),
            'textSpeed'      => $options->getTextSpeed(),
            'keyboardLayout' => $options->getKeyboardLayout(),
        ]);
    }

    // PUT /api/options → met à jour les options
    #[Route('/api/options', name: 'api_options_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $options = $user->getOptions();

        // Crée les options si elles n'existent pas
        if (!$options) {
            $options = new UserOptions();
            $options->setUser($user);
            $em->persist($options);
        }

        if (isset($data['volumeMaster']))   $options->setVolumeMaster((int) $data['volumeMaster']);
        if (isset($data['volumeMusic']))    $options->setVolumeMusic((int) $data['volumeMusic']);
        if (isset($data['volumeSfx']))      $options->setVolumeSfx((int) $data['volumeSfx']);
        if (isset($data['fullscreen']))     $options->setFullscreen((bool) $data['fullscreen']);
        if (isset($data['showFps']))        $options->setShowFps((bool) $data['showFps']);
        if (isset($data['textSpeed']))      $options->setTextSpeed($data['textSpeed']);
        if (isset($data['keyboardLayout'])) $options->setKeyboardLayout($data['keyboardLayout']);

        $em->flush();

        return $this->json(['message' => 'Options mises à jour avec succès']);
    }
}