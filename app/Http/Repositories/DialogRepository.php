<?php

namespace App\Http\Repositories;

use App\Book;
use App\Enquiry;
use App\User;
use App\Dialog;

class DialogRepository
{
    public function get($id)
    {
        return Dialog::find($id);
    }
    
    public function all()
    {
        return Dialog::all();
    }

    public function create(array $data)
    {
        return Dialog::create($data);
    }

    public function update(Dialog $dialog, array $data)
    {
        $dialog->fill($data);
        $dialog->save();
        return $dialog;
    }

    public function attach(Dialog $dialog, Book $book)
    {
        $dialog->book()->associate($book);
        $dialog->save();
        return $dialog;
    }

    public function attachEnquiry(Dialog $dialog, Enquiry $enquiry)
    {
        $dialog->enquiry()->associate($enquiry);
        $dialog->save();
        return $dialog;
    }

    public function assignCoach(Dialog $dialog, User $coach)
    {
        $dialog->coach()->associate($coach);
        $dialog->save();
        return $dialog;
    }

    public function byBook(Book $book)
    {
        return $book->dialog()->with('messages')->first();
    }

    public function byEnquiry(Enquiry $enquiry)
    {
        return $enquiry->dialog()->with('messages')->first();
    }
}
