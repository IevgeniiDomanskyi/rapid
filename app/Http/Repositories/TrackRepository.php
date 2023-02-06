<?php

namespace App\Http\Repositories;

use App\Track;
use App\TrackDate;

class TrackRepository
{
    public function get($id)
    {
        return Track::find($id);
    }
    
    public function all()
    {
        return Track::all();
    }

    public function create(array $data)
    {
        return Track::create($data);
    }

    public function allDate()
    {
        return TrackDate::all();
    }

    public function createDate(array $data)
    {
        return TrackDate::create($data);
    }

    public function attachDate(Track $track, TrackDate $trackDate)
    {
        $trackDate->track()->associate($track);
        $trackDate->save();
        return $trackDate;
    }

    public function removeDate(TrackDate $date)
    {
        return $date->delete();
    }

    public function byRegion($region)
    {
        return Track::where('region_id', $region)->get();
    }

    public function datesAvailable(Track $track)
    {
        return $track->dates()->whereDate('date', '>', now())->get();
    }

    public function getDate($id)
    {
        return TrackDate::find($id);
    }

    public function export($ids)
    {
        return empty($ids) ? TrackDate::all() : TrackDate::whereIn('id', $ids)->get();
    }
}
