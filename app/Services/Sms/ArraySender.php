<?php

namespace App\Services\Sms;

class ArraySender implements SmsSender
{
    private array $messages = [];

    public function send($number, $text): void
    {
        $this->messages[] = [
            'to' => trim($number, '+'),
            'msg' => $text,
        ];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
