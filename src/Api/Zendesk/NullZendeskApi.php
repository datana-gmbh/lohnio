<?php

declare(strict_types=1);

namespace App\Api\Zendesk;

use App\Bridge\Zendesk\Domain\Value\Ticket;

final class NullZendeskApi implements ZendeskApiInterface
{
    public function createTicket(Ticket $ticket): void
    {
    }
}
