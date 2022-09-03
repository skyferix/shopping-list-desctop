<?php

declare(strict_types=1);

namespace App\UseCase\Sidebar;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class SidebarFactoryUseCase implements SidebarFactoryInterface
{
    /** @var SidebarItemInterface[] $builders */
    private iterable $builders;

    public function __construct(#[TaggedIterator(tag: 'app.sidebar_builder')] iterable $builders)
    {
        $this->builders = $builders;
    }

    /**
     * @return SidebarItem[]
     */
    public function build(): array
    {
        $sidebarItems = [];

        foreach ($this->builders as $builder) {
            if (!$builder->getSidebarSupport()) {
                continue;
            }

            $sidebarItems[] = $builder->getSidebarItem();
        }

        return $sidebarItems;
    }

}