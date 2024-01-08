<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum Flashmessage: string
{
    case SUCCESS = 'user_success';
    case NOTICE = 'user_notice';
    case ERROR = 'user_error';
}
