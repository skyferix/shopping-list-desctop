<?php

declare(strict_types=1);

namespace App\UseCase\Sidebar;

class SidebarItem
{
    public function __construct(
        private string  $relativePath,
        private string  $imageSource,
        private ?string $translationAlias = null
    ) {
        $translation = $this->translationAlias ?: $this->relativePath;
        $this->translationAlias = 'module.' . $translation;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getImageSource(): string
    {
        return $this->imageSource;
    }

    public function getTranslationAlias(): string
    {
        return $this->translationAlias;
    }
}