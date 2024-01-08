<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum Anrede: string
{
    case Herr = 'Herr';
    case Frau = 'Frau';
}
