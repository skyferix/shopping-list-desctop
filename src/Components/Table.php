<?php

declare(strict_types=1);

namespace App\Components;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('table')]
class Table
{
    public array $data;

    public string $title;

    public function getHeaders(): array
    {
        return array_keys($this->data[0] ?? []);
    }
}