<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\DocRepository;
use App\User;
use App\Doc;
use App\Book;

class DocService extends Service
{
    protected $docRepository;

    public function __construct(DocRepository $docRepository)
    {
        $this->docRepository = $docRepository;
    }

    public function all(User $user)
    {
        return $user->docs()->orderBy('created_at', 'desc')->get();
    }

    public function save(User $user, $object, array $input)
    {
        $doc = $object->docs()->create($input);
        $doc = $this->docRepository->connect($doc, $user);
        return $doc;
    }

    public function sign(User $user, Doc $doc)
    {
        $doc = $this->docRepository->update($doc, [
            'is_signed' => true,
        ]);
        
        if (strpos($doc->type, 'road') !== FALSE) {
            $road = str_replace('road', '', $doc->type);

            service('Book')->road($doc->book, $road);
        }

        return $doc;
    }

    public function generate($object, $rewrite = false)
    {
        if ( ! empty($rewrite)) {
            $this->docRepository->clear($object);
        }
        
        $object->load('dates');
        foreach ($object->dates as $date) {
            $doc = $object->docs()->create([
                'type' => 'road'.$date->pivot->road,
                'is_signed' => false,
                'date' => $date->date,
            ]);

            $doc = $this->docRepository->connect($doc, $book->user);
        }
    }

    public function reports()
    {
        return $this->docRepository->reports();
    }
}
