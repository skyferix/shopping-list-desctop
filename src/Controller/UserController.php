<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(TranslatorInterface $translator): Response
    {
        $response = $this->api->request($this->getUserToken(), 'GET', '/user', []);
        if ($response->hasNoAuth()) {
            return $this->logout();
        }

        if (!$response->isSuccess()) {
            $this->error = $this->api->generateError();
        }

        if (!$response->isSuccess()) {
            $this->error = $this->api->generateError();
        }

        $users = $response->getContent(true);

        $users = array_map(
            function ($user) use ($translator) {
                $roles = array_map(
                    fn($role) => $translator->trans($role),
                    $user['roles']
                );
                $roles[] = $translator->trans('ROLE_USER', [], 'security');
                $user['roles'] = implode(', ', $roles);
                return $user;
            },
            $users
        );

        return $this->render('user/index.html.twig', [
            'error' => $this->error,
            'users' => $users
        ]);
    }
}
