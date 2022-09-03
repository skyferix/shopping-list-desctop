<?php

declare(strict_types=1);

namespace App\Twig;

use App\UseCase\Sidebar\SidebarFactoryUseCase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    public function __construct(private SidebarFactoryUseCase $sidebarFactoryUseCase)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getSidebarMenuItems', [$this, 'getSidebarMenuItems'])
        ];
    }

    public function getSidebarMenuItems(): array
    {
        return $this->sidebarFactoryUseCase->build();
    }
}