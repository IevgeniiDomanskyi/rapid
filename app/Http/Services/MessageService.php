<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\MessageRepository;
use App\Dialog;
use App\Message;

class MessageService extends Service
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function all()
    {
        return $this->messageRepository->all();
    }

    public function get(int $id)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            return $this->messageRepository->get($id);
        }

        return [];
    }

    public function create(Dialog $dialog, array $input)
    {
        $message = $this->messageRepository->create($input);
        $message = $this->messageRepository->attach($message, $dialog);

        return $message;
    }

    public function read(Message $message)
    {
        return $this->messageRepository->update($message, [
            'receiver_read' => now(),
        ]);
    }
}
