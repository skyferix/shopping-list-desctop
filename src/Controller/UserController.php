<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

            return $this->render('user/index.html.twig', [
                'error' => $this->error,
            ]);
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
            'users' => $users
        ]);
    }

    #[Route('/user/{id}', name: 'user_view')]
    public function view(int $id): Response
    {
        if ($this->isSimpleUser()) {
            /** @var User $user */
            $user = $this->getUser();
            if ($user->getId() !== $id) {
                return new RedirectResponse($this->generateUrl('user_view', ['id' => $user->getId()]));
            }
        }

        $response = $this->api->request($this->getUserToken(), 'GET', '/user/' . $id, []);

        if ($response->hasNoAuth()) {
            return $this->logout();
        }

        if ($response->notFound()) {
            throw $this->createNotFoundException();
        }

        if (!$response->isSuccess()) {
            $this->error = $this->api->generateError();

            return $this->render('user/view.html.twig', [
                'error' => $this->error,
            ]);
        }

        $user = $response->getContent(true);

        return $this->render('user/view.html.twig', [
            'user' => $user
        ]);
    }
}
