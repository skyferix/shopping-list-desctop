<?php

declare(strict_types=1);

namespace App\Components;

use App\UseCase\Sidebar\SidebarItem;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('sidebar-menu')]
class SidebarMenu
{
    /** @var SidebarItem[] $items */
    public array $items;
}