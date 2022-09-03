<?php

declare(strict_types=1);

namespace App\UseCase\Sidebar;

interface SidebarItemInterface
{
    public function getSidebarItem(): SidebarItem;

    public function getSidebarSupport(): bool;
}