<?php

namespace App\Http\Services;

use App\Http\Services\Service;

class ExportService extends Service
{
    public function generate($options, $items)
    {
        $me = auth()->user();
        $roles = $options['roles'];
        if (in_array('admin', $roles) && $me->role == 2 || in_array('coach', $roles) && $me->role == 1) {
            $data = [];
            if ($options['type'] == 'enquiry') {
                $data = service('Book')->exportEnquiry($items);
            } elseif ($options['type'] == 'redemption') {
                $data = service('Voucher')->exportRedemption($items);
            } elseif ($options['type'] == 'event-booking') {
                $data = service('Event')->exportBooking($items);
            } elseif ($options['type'] == 'event-enquiry') {
                $data = service('Event')->exportEnquiry($items);
            } else {
                $data = service($options['type'])->export($items);
            }

            if ( ! empty($data)) {
                $fp = fopen('php://output', 'w');
                fputcsv($fp, array_keys(reset($data)));

                foreach ($data as $values) {
                    fputcsv($fp, $values);
                }

                fclose($fp);
                exit;
            }
        } else {
            return response()->message('You haven\'t access to make export', 'error', 403);
        }
    }
}
