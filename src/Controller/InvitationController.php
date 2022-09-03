<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Sidebar\SidebarItem;
use App\UseCase\Sidebar\SidebarItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invitation', name: 'invitation')]
class InvitationController extends AbstractController implements SidebarItemInterface
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('invitation/index.html.twig', [
            'controller_name' => 'InvitationController',
        ]);
    }

    public function getSidebarItem(): SidebarItem
    {
        return new SidebarItem('invitation', 'invitation-256.png');
    }

    public function getSidebarSupport(): bool
    {
        return $this->isGranted('ROLE_ADMIN');
    }
}
