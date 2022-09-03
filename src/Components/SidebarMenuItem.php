<?php

declare(strict_types=1);

namespace App\Components;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('sidebar-menu-item')]
class SidebarMenuItem
{
    public string $path;

    public string $src;

    public string $trans;

    public int $dimension = 22;

    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function translation(): string
    {
        return $this->translator->trans($this->trans, [], 'sidebar');
    }
}