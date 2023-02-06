<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\TrackRepository;
use App\TrackDate;

class TrackService extends Service
{
    protected $trackRepository;

    public function __construct(TrackRepository $trackRepository)
    {
        $this->trackRepository = $trackRepository;
    }

    public function all()
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->trackRepository->all();
        }

        return [];
    }

    public function get(int $id)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->trackRepository->get($id);
        }

        return [];
    }

    public function save(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $data = [
                'name' => $input['name'],
                'region_id' => $input['region'],
                'email' => $input['email'],
            ];

            $track = $this->trackRepository->create($data);
            return $track;
        }

        return response()->message('You haven\'t access to create tracks', 'error', 403);
    }

    public function allDate()
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->trackRepository->allDate();
        }

        return [];
    }

    public function getDate($id)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->trackRepository->getDate($id);
        }

        return [];
    }

    public function saveDate(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $data = [
                'date' => $input['date'],
                'riders' => $input['riders'],
            ];

            $track = $this->trackRepository->get($input['track']);
            $date = $this->trackRepository->createDate($data);
            $date = $this->trackRepository->attachDate($track, $date);
            return $date;
        }

        return response()->message('You haven\'t access to create track dates', 'error', 403);
    }

    public function removeDate(TrackDate $date)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->trackRepository->removeDate($date);
        }

        return response()->message('You haven\'t access to remove track dates', 'error', 403);
    }

    public function dateByRegion(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            // $tracks = $this->trackRepository->byRegion($input['region']);
            $tracks = $this->trackRepository->all();

            $dates = [];
            foreach ($tracks as $track) {
                $trackDates = $this->trackRepository->datesAvailable($track);

                foreach ($trackDates as $date) {
                    if ($date->riders > $date->books->count()) {
                        $dates[$date->timestamp] = $date;
                    }
                }
            }
            ksort($dates);

            return array_values($dates);
        }

        return [];
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->trackRepository->export($ids);
        foreach ($rows as $row) {
            $items[] = [
                'ID' => $row->id,
                'Date' => $row->date->format('d/m/Y'),
                'Course' => $row->course > 0 ? 'Bikemaster '.$row->course : '-',
                'Track' => $row->track->name,
                'Postcode' => ! empty($row->track->postcode) ? $row->track->postcode->code.' ('.$row->track->postcode->area.')' : '',
                'Max riders' => $row->riders,
                'Left' => $row->left,
            ];
        }

        return $items;
    }
}
