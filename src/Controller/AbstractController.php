<?php

namespace App\Controller;

use App\Request\ApiRequest;
use App\Security\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected ApiRequest $api;
    protected ?string $error;

    public function __construct(ApiRequest $api)
    {
        $this->api = $api;
        $this->error = null;
    }

    protected function logout(): RedirectResponse
    {
        $this->addFlash('error', 'error.460');
        return new RedirectResponse($this->generateUrl('logout'));
    }

    protected function getUserToken(): string
    {
        /** @var User $user */
        $user = $this->getUser();

        return $user->getToken();
    }

    protected function isSimpleUser(): bool
    {
        return !($this->isGranted('ROLE_SUPER_ADMIN') && $this->isGranted('ROLE_ADMIN'));
    }
}