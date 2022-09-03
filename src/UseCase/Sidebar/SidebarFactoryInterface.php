<?php

declare(strict_types=1);

namespace App\UseCase\Sidebar;

interface SidebarFactoryInterface
{
    public function build(): array;
}