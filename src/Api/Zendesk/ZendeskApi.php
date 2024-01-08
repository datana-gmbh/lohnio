<?php

declare(strict_types=1);

namespace App\Api\Zendesk;

use App\Bridge\Zendesk\Domain\Value\Ticket;
use Zendesk\API\HttpClient;

final readonly class ZendeskApi implements ZendeskApiInterface
{
    public function __construct(
        private HttpClient $client,
    ) {
    }

    public function createTicket(Ticket $ticket): void
    {
        $this->client->tickets()->create([
            'subject' => $ticket->subject,
            'requester' => [
                'name' => $ticket->name,
                'email' => $ticket->email,
            ],
            'comment' => [
                'body' => $ticket->body,
            ],
        ]);
    }
}
