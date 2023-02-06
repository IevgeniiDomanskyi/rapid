<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\DialogRepository;
use App\Notifications\Dialog\Message as DialogMessageNotification;
use App\Notifications\Admin\Message as AdminMessageNotification;
use App\Book;
use App\Enquiry;
use App\User;
use App\Dialog;
use App\Message;

class DialogService extends Service
{
    protected $dialogRepository;

    public function __construct(DialogRepository $dialogRepository)
    {
        $this->dialogRepository = $dialogRepository;
    }

    public function all(User $user)
    {
        $dialogs = [];
        foreach ($user->books as $book) {
            $dialogs[] = $this->dialogRepository->byBook($book);
        }

        foreach ($user->enquiries as $enquiries) {
            $dialogs[] = $this->dialogRepository->byEnquiry($enquiries);
        }
        return $dialogs;
    }

    public function get(int $id)
    {
        return $this->dialogRepository->get($id);
    }

    public function byBook(Book $book)
    {
        return $this->dialogRepository->byBook($book);
    }

    public function create(Book $book, User $customer)
    {
        $dialog = $this->dialogRepository->create([
            'customer_id' => $customer->id,
        ]);
        $dialog = $this->dialogRepository->attach($dialog, $book);

        return $dialog;
    }

    public function createEnquiry(Enquiry $enquiry, User $customer)
    {
        $dialog = $this->dialogRepository->create([
            'customer_id' => $customer->id,
        ]);
        $dialog = $this->dialogRepository->attachEnquiry($dialog, $enquiry);

        return $dialog;
    }

    public function update(Dialog $dialog, array $input)
    {
        return $this->dialogRepository->update($dialog, $input);
    }

    public function assignCoach(Dialog $dialog, User $coach)
    {
        $dialog = $this->dialogRepository->assignCoach($dialog, $coach);
        service('Message')->create($dialog, [
            'author_id' => auth()->id(),
            'receiver_id' => $dialog->customer->id,
            'message' => 'Coach assigned on '.now()->format('d/m'),
            'type' => Message::TYPE_COACH,
            'author_read' => now(),
        ]);
        return $dialog;
    }

    public function call(Dialog $dialog, array $input)
    {
        service('Message')->create($dialog, [
            'author_id' => auth()->id(),
            'receiver_id' => $dialog->customer->id,
            'message' => $input['message'],
            'type' => Message::TYPE_CALL,
            'author_read' => now(),
        ]);
        return $dialog;
    }

    public function notes(Dialog $dialog, array $input)
    {
        service('Message')->create($dialog, [
            'author_id' => auth()->id(),
            'receiver_id' => $dialog->customer->id,
            'message' => $input['message'],
            'type' => Message::TYPE_NOTE,
            'author_read' => now(),
        ]);
        return $dialog;
    }

    public function message(Dialog $dialog, array $input, $silent = false, $user = null)
    {
        $me = auth()->user();
        if ( ! empty($user)) {
            $me = $user;
        }
        
        $receiver = $me->role > 0 ? $dialog->customer : ($dialog->coach ? $dialog->coach : null);
        $receiver_id = $receiver ? $receiver->id : 0;

        service('Message')->create($dialog, [
            'author_id' => $me->id,
            'receiver_id' => $receiver_id,
            'message' => $input['message'],
            'type' => Message::TYPE_MESSAGE,
            'author_read' => now(),
        ]);
        
        if ($receiver_id > 0) {
            $auth_token = md5(time().$receiver->id.$receiver->email);
            service('User')->update($receiver, [
                'auth_token' => $auth_token,
            ]);
            
            if ( ! $silent) {
                $receiver->notify((new DialogMessageNotification($dialog, $auth_token)));
            }
        }

        if ($me->role == 0) {
            $admins = service('User')->admins();
            foreach ($admins as $admin) {
                if ( ! $silent) {
                    if ($admin->email != 'natalie@adkca.co.uk') {
                        $admin->notify(new AdminMessageNotification($dialog));
                    }
                }
            }
        }

        return $dialog;
    }
}
