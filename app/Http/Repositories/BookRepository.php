<?php

namespace App\Http\Repositories;

use App\Temp;
use App\Book;
use App\Enquiry;
use App\TrackDate;
use App\User;
use App\Date;

class BookRepository
{
    public function getTemp(string $token)
    {
        return Temp::where('token', $token)->first();
    }

    public function updateTemp(Temp $temp, array $data)
    {
        $temp->fill($data);
        $temp->save();
        return $temp;
    }

    public function removeTemp(Temp $temp)
    {
        return $temp->delete();
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function connect(Book $book, User $user)
    {
        $book->user()->associate($user);
        $book->save();
        return $book;
    }

    public function createEnquiry(array $data)
    {
        return Enquiry::create($data);
    }

    public function connectEnquiry(Enquiry $enquiry, User $user)
    {
        $enquiry->user()->associate($user);
        $enquiry->save();
        return $enquiry;
    }

    public function get(int $id)
    {
        return Book::find($id);
    }

    public function all()
    {
        return Book::orderBy('created_at', 'desc')->get();
    }

    public function enquiryAll()
    {
        return Enquiry::orderBy('created_at', 'desc')->get();
    }

    public function assign(Book $book, User $coach)
    {
        $book->coach()->associate($coach);
        $book->save();
        return $book;
    }

    public function defineTrackDate(Book $book, TrackDate $trackDate)
    {
        $book->trackDate()->associate($trackDate);
        $book->save();
        return $book;
    }

    public function update(Book $book, array $data)
    {
        $book->fill($data);
        $book->save();
        return $book;
    }

    public function roadDate(Book $book, int $road)
    {
        return $book->dates()->wherePivot('road', $road)->first();
    }

    public function attachDate(Book $book, Date $date, $input)
    {
        return $book->dates()->attach($date, $input);
    }

    public function roadDateRemove(Book $book, $road)
    {
        $dates = $book->dates()->wherePivot('road', $road)->get();
        foreach ($dates as $date) {
            $book->dates()->wherePivot('road', $road)->detach($date);
        }
        return true;
    }

    public function getForPay(User $user)
    {
        return $user->books()->where('status', 5)->get();
    }

    public function export($ids)
    {
        if (empty($ids)) {
            return Book::all();
        } else {
            return Book::whereIn('id', $ids)->get();
        }
    }

    public function exportEnquiry($ids)
    {
        if (empty($ids)) {
            return Enquiry::all();
        } else {
            return Enquiry::whereIn('id', $ids)->get();
        }
    }
}
