<?php

namespace App\UseCases\Tickets;

use App\Entity\Ticket\Ticket;

class TicketService
{
    public function create(int $userId, array $data): Ticket
    {
        return Ticket::new($userId, $data['subject'], $data['content']);
    }

    public function edit(int $id, array $data): void
    {
        $ticket = $this->getTicket($id);

        $ticket->edit($data['subject'], $data['content']);
    }

    public function message(int $userId, int $id, array $data): void
    {
        $ticket = $this->getTicket($id);

        $ticket->addMessage($userId, $data['message']);
    }

    public function approve(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);

        $ticket->approve($userId);
    }

    public function close(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);

        $ticket->close($userId);
    }

    public function reopen(int $userId, int $id): void
    {
        $ticket = $this->getTicket($id);

        $ticket->reopen($userId);
    }

    public function removeByAdmin(int $id): void
    {
        $ticket = $this->getTicket($id);

        $ticket->delete();
    }

    public function removeByOwner(int $id): void
    {
        $ticket = $this->getTicket($id);

        if (! $ticket->canBeRemoved()) {
            throw new \DomainException('Unable to remove active ticket.');
        }

        $ticket->delete();
    }

    private function getTicket(int $id): Ticket
    {
        return Ticket::findOrFail($id);
    }
}
