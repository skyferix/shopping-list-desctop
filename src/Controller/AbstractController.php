<?php

declare(strict_types=1);

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
        return !$this->isGranted('ROLE_ADMIN');
    }

    protected function request(string $method, string $relativeUrl, array $options = []): ApiRequest
    {
        $response = $this->api->request($this->getUserToken(), $method, $relativeUrl, $options);
        if ($response->notFound()) {
            throw $this->createNotFoundException();
        }

        return $response;
    }
}