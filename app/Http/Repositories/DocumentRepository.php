<?php

namespace App\Http\Repositories;

use App\Document;
use App\User;
use App\Book;

class DocumentRepository
{
    public function create(array $data)
    {
        return Document::create($data);
    }

    public function connect(Document $document, User $user)
    {
        $document->user()->associate($user);
        $document->save();
        return $document;
    }

    public function connectToBook(Document $document, Book $book)
    {
        $document->book()->associate($book);
        $document->save();
        return $document;
    }

    public function update(Document $document, array $data)
    {
        $document->fill($data);
        $document->save();
        return $document;
    }

    public function clear(Book $book)
    {
        Document::where('book_id', $book->id)->where('type', 'like', '%road%')->delete();
    }

    public function reports()
    {
        return Document::whereIn('type', ['certificate', 'congratulation', 'report'])->orderBy('date', 'desc')->get();
    }
}
