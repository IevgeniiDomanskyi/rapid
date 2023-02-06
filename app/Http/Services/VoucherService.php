<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\VoucherRepository;
use App\Voucher;

class VoucherService extends Service
{
    protected $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function all()
    {
        return $this->voucherRepository->all();
    }

    public function get($id)
    {
        return $this->voucherRepository->get($id);
    }

    public function getByCode($code)
    {
        return $this->voucherRepository->getByCode($code);
    }

    public function save(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            if ( ! is_array($input['code'])) {
                $input['code'] = [$input['code']];    
            }
            
            foreach ($input['code'] as $code) {
                $data = [
                    'type' => $input['type'],
                    'amount' => $input['amount'],
                    'code' => $code,
                    'expired_at' => $input['expired_at'],
                    'coupon_limit' => empty($input['coupon_limit']) ? 0 : $input['coupon_limit'],
                    'user_limit' => empty($input['user_limit']) ? 0 : $input['user_limit'],
                ];

                $voucher = $this->voucherRepository->create($data);
                $voucher = $this->voucherRepository->sync($voucher, $input['excludes']);
            }

            return true;
        }

        return response()->message('You haven\'t access to create vauchers', 'error', 403);
    }

    public function update(Voucher $voucher, array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $data = [
                'type' => $input['type'],
                'amount' => $input['amount'],
                'code' => $input['code'],
                'expired_at' => $input['expired_at'],
                'coupon_limit' => empty($input['coupon_limit']) ? 0 : $input['coupon_limit'],
                'user_limit' => empty($input['user_limit']) ? 0 : $input['user_limit'],
            ];

            $voucher = $this->voucherRepository->update($voucher, $data);
            $voucher = $this->voucherRepository->sync($voucher, $input['excludes']);

            return true;
        }

        return response()->message('You haven\'t access to create vauchers', 'error', 403);
    }

    public function remove(Voucher $voucher)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $this->voucherRepository->remove($voucher);
            return true;
        }

        return response()->message('You haven\'t access to remove voucher', 'error', 403);
    }

    public function upload($file)
    {  
        if ( ! file_exists($file) || ! is_readable($file)) {
            return response()->message('Error during uploading file', 'error', 422);
        }

        $data = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row[0];
            }
            fclose($handle);
        }

        return $data;
    }

    public function check($code, $user = false)
    {
        $user = empty($user) ? auth()->user() : $user;

        $voucher = $this->getByCode($code);
        if ( ! empty($voucher)) {
            if ( ! empty($voucher->expired_at) && $voucher->expired_at->isPast()) {
                return response()->message('Your voucher was expired', 'error', 422);
            } else {
                if ($voucher->coupon_limit > 0 && $voucher->users->count() < $voucher->coupon_limit || $voucher->coupon_limit == 0) {
                    if ($voucher->user_limit > 0 && $voucher->users()->where('user_id', $user->id)->count() < $voucher->user_limit || $voucher->user_limit == 0) {
                        return $voucher;
                    } else {
                        return response()->message('This voucher is out of limit', 'error', 422);
                    }
                } else {
                    return response()->message('This voucher is out of limit', 'error', 422);
                }
            }
        } else {
            return response()->message('Voucher code is wrong', 'error', 422);
        }
    }

    public function users()
    {
        $users = [];
        $vouchers = $this->voucherRepository->all();
        foreach ($vouchers as $voucher) {
            foreach ($voucher->users as $user) {
                $book = service('Book')->get($user->pivot->book_id);

                $users[] = [
                    'id' => $user->pivot->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'created_at' => $user->pivot->created_at,
                    'date' => $user->pivot->created_at->format('d/m/Y'),
                    'time' => $user->pivot->created_at->format('H:i'),
                    'code' => $voucher->code,
                    'order_number' => $book->orderId(),
                    'course' => $book->course->title,
                ];
            }
        }

        usort($users, function ($a, $b) {
            return strcmp($a["created_at"], $b["created_at"]);
        });

        return $users;
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->voucherRepository->export($ids);
        foreach ($rows as $row) {
            $items[] = [
                'Discount type' => $row->type == 0 ? 'Fixed amount' : 'Percentage',
                'Discount offered' => $row->amount,
                'Voucher code' => $row->code,
                'Expiry date' => ! empty($row->expired_at) ? $row->expired_at->format('d/m/Y') : '',
                'Excluded items' => $this->excludedItemsText($row->levels),
                'Usage limit per coupon' => ! empty($row->coupon_limit) ? $row->coupon_limit : '',
                'Usage limit per user' => ! empty($row->user_limit) ? $row->user_limit : '',
            ];
        }

        return $items;
    }

    public function excludedItemsText($levels)
    {
        $temp = [];
        foreach ($levels as $level) {
            $temp[] = $level->course->title.( ! empty($level->level) ? ' '.$level->level : '');
        }
        return implode (' | ', $temp);
    }

    public function exportRedemption($ids)
    {
        $users = [];
        $vouchers = $this->voucherRepository->all();
        foreach ($vouchers as $voucher) {
            foreach ($voucher->users as $user) {
                if (in_array($user->pivot->id, $ids)) {
                    $book = service('Book')->get($user->pivot->book_id);

                    $users[] = [
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'date' => $user->pivot->created_at->format('d/m/Y'),
                        'time' => $user->pivot->created_at->format('H:i'),
                        'code' => $voucher->code,
                        'order_number' => $book->orderId(),
                        'course' => $book->course->title,
                    ];
                }
            }
        }

        return $users;
    }
}
