<?php

namespace App\Http\Repositories;

use App\Doc;
use App\User;
use App\Book;

class DocRepository
{
    public function create(array $data)
    {
        return Document::create($data);
    }

    public function connect(Doc $doc, User $user)
    {
        $doc->user()->associate($user);
        $doc->save();
        return $doc;
    }

    public function update(Doc $doc, array $data)
    {
        $doc->fill($data);
        $doc->save();
        return $doc;
    }

    public function clear($object)
    {
        $object->docs()->where('type', 'like', '%road%')->delete();
    }

    public function reports()
    {
        return Doc::whereIn('type', ['certificate', 'congratulation', 'report'])->orderBy('date', 'desc')->get();
    }
}
