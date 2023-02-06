<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\DocumentRepository;
use App\User;
use App\Document;
use App\Book;

class DocumentService extends Service
{
    protected $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function all(User $user)
    {
        return $user->documents()->orderBy('created_at', 'desc')->get();
    }

    public function save(User $user, Book $book, array $input)
    {
        $document = $this->documentRepository->create($input);
        $document = $this->documentRepository->connect($document, $user);
        $document = $this->documentRepository->connectToBook($document, $book);
        return $document;
    }

    public function sign(User $user, Document $document)
    {
        $document = $this->documentRepository->update($document, [
            'is_signed' => true,
        ]);
        
        if (strpos($document->type, 'road') !== FALSE) {
            $road = str_replace('road', '', $document->type);

            service('Book')->road($document->book, $road);
        }

        return $document;
    }

    public function generate(Book $book, $rewrite = false)
    {
        if ( ! empty($rewrite)) {
            $this->documentRepository->clear($book);
        }
        
        $book->load('dates');
        foreach ($book->dates as $date) {
            $document = $this->documentRepository->create([
                'type' => 'road'.$date->pivot->road,
                'is_signed' => false,
                'date' => $date->date,
            ]);

            $document = $this->documentRepository->connect($document, $book->user);
            $document = $this->documentRepository->connectToBook($document, $book);
        }
    }

    public function reports()
    {
        return $this->documentRepository->reports();
    }
}
