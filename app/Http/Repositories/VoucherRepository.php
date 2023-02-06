<?php

namespace App\Http\Repositories;

use App\Voucher;

class VoucherRepository
{
    public function all()
    {
        return Voucher::all();
    }

    public function get($id)
    {
        return Voucher::find($id);
    }

    public function getByCode($code)
    {
        return Voucher::where('code', $code)->first();
    }

    public function create(array $data)
    {
        return Voucher::create($data);
    }

    public function update(Voucher $voucher, array $data)
    {
        $voucher->fill($data);
        $voucher->save();
        return $voucher;
    }

    public function sync(Voucher $voucher, $excludes)
    {
        return $voucher->levels()->sync($excludes);
    }

    public function remove(Voucher $voucher)
    {
        return $voucher->delete();
    }

    public function export($ids)
    {
        return empty($ids) ? Voucher::all() : Voucher::whereIn('id', $ids)->get();
    }
}
