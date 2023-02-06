<?php

namespace App\Http\Repositories;

use DB;
use App\Event;
use App\User;
use App\Temp;
use App\EventEnquiry;

class EventRepository
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

    public function get($id)
    {
        return Event::find($id);
    }
    
    public function all()
    {
        return Event::all();
    }

    public function create(array $data)
    {
        return Event::create($data);
    }

    public function update(Event $event, array $data)
    {
        $event->fill($data);
        $event->save();
        return $event;
    }

    public function connect(Event $event, User $user, array $data)
    {
        $event->customers()->attach([$user->id => $data]);
        return $event;
    }

    public function detach(Event $event)
    {
        return $event->customers()->detach();
    }

    public function remove(Event $event)
    {
        return $event->delete();
    }

    public function createEnquiry(array $data)
    {
        return EventEnquiry::create($data);
    }

    public function connectEnquiry(EventEnquiry $enquiry, User $user)
    {
        $enquiry->user()->associate($user);
        $enquiry->save();
        return $enquiry;
    }

    public function enquiryAll()
    {
        return EventEnquiry::orderBy('created_at', 'desc')->get();
    }

    public function export($ids)
    {
        return empty($ids) ? Event::all() : Event::whereIn('id', $ids)->get();
    }

    public function exportBooking($ids)
    {
        $q = DB::table('event_user')->orderBy('created_at', 'desc');
        if ( ! empty($ids)) {
            $q->whereIn('id', $ids);
        }
        return $q->get();
    }

    public function exportEnquiry($ids)
    {
        if (empty($ids)) {
            return EventEnquiry::all();
        } else {
            return EventEnquiry::whereIn('id', $ids)->get();
        }
    }
}
