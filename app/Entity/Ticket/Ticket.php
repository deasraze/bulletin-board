<?php

namespace App\Entity\Ticket;

use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $content
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method Builder forUser(User $user)
*/
class Ticket extends Model
{
    protected $table = 'ticket_tickets';

    protected $guarded = ['id'];

    public static function new(int $userId, string $subject, string $content): self
    {
        $ticket = self::create([
            'user_id' => $userId,
            'subject' => $subject,
            'content' => $content,
            'status' => Status::OPEN,
        ]);

        $ticket->setStatus(Status::OPEN, $userId);

        return $ticket;
    }

    public function edit(string $subject, string $content): void
    {
        $this->update([
            'subject' => $subject,
            'content' => $content,
        ]);
    }

    public function addMessage(int $userId, string $message): void
    {
        if (! $this->allowsMessages()) {
            throw new \DomainException('Ticket is closed for messages.');
        }

        $this->messages()->create([
            'user_id' => $userId,
            'message' => $message,
        ]);

        $this->update();
    }

    public function approve(int $userId): void
    {
        if ($this->isApproved()) {
            throw new \DomainException('Ticket is already approved.');
        }

        $this->setStatus(Status::APPROVED, $userId);
    }

    public function close(int $userId): void
    {
        if ($this->isClosed()) {
            throw new \DomainException('Ticket is already closed.');
        }

        $this->setStatus(Status::CLOSED, $userId);
    }

    public function reopen(int $userId): void
    {
        if (! $this->isClosed()) {
            throw new \DomainException('Ticket is not closed.');
        }

        $this->setStatus(Status::OPEN, $userId);
    }

    public function allowsMessages(): bool
    {
        return ! $this->isClosed();
    }

    public function isOpen(): bool
    {
        return $this->status === Status::OPEN;
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isClosed(): bool
    {
        return $this->status === Status::CLOSED;
    }

    public function canBeRemoved(): bool
    {
        return $this->isOpen();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    private function setStatus(string $status, ?int $userId): void
    {
        $this->statuses()->create([
            'status' => $status,
            'user_id' => $userId,
        ]);

        $this->update(['status' => $status]);
    }
}
