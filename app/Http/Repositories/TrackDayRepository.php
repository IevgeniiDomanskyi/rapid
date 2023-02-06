<?php

namespace App\Http\Repositories;

use DB;
use App\TrackDay;
use App\User;
use App\Temp;
use App\TrackDayEnquiry;

class TrackDayRepository
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
        return TrackDay::find($id);
    }
    
    public function all()
    {
        return TrackDay::all();
    }

    public function create(array $data)
    {
        return TrackDay::create($data);
    }

    public function update(TrackDay $trackDay, array $data)
    {
        $trackDay->fill($data);
        $trackDay->save();
        return $trackDay;
    }

    public function connect(TrackDay $trackDay, User $user, array $data)
    {
        $trackDay->customers()->attach([$user->id => $data]);
        return $trackDay;
    }

    public function detach(TrackDay $trackDay)
    {
        return $trackDay->customers()->detach();
    }

    public function remove(TrackDay $trackDay)
    {
        return $trackDay->delete();
    }

    public function createEnquiry(array $data)
    {
        return TrackDayEnquiry::create($data);
    }

    public function connectEnquiry(TrackDayEnquiry $enquiry, User $user)
    {
        $enquiry->user()->associate($user);
        $enquiry->save();
        return $enquiry;
    }

    public function enquiryAll()
    {
        return TrackDayEnquiry::orderBy('created_at', 'desc')->get();
    }

    public function export($ids)
    {
        return empty($ids) ? TrackDay::all() : TrackDay::whereIn('id', $ids)->get();
    }

    public function exportBooking($ids)
    {
        $q = DB::table('track_day_user')->orderBy('created_at', 'desc');
        if ( ! empty($ids)) {
            $q->whereIn('id', $ids);
        }
        return $q->get();
    }

    public function exportEnquiry($ids)
    {
        if (empty($ids)) {
            return TrackDayEnquiry::all();
        } else {
            return TrackDayEnquiry::whereIn('id', $ids)->get();
        }
    }
}
