<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\Save as VoucherSaveRequest;
use App\Http\Resources\Voucher\Item as VoucherItemResource;
use App\Voucher;

class VoucherController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Voucher')->all();
        return response()->result(VoucherItemResource::collection($result));
    }

    public function save(VoucherSaveRequest $request)
    {
        $input = $request->only(['type', 'amount', 'code', 'excludes', 'expired_at', 'coupon_limit', 'user_limit']);

        $result = service('Voucher')->save($input);
        $result = service('Voucher')->all();
        return response()->result(VoucherItemResource::collection($result), 'Voucher(s) was saved');
    }

    public function update(VoucherSaveRequest $request, Voucher $voucher)
    {
        $input = $request->only(['type', 'amount', 'code', 'excludes', 'expired_at', 'coupon_limit', 'user_limit']);

        $result = service('Voucher')->update($voucher, $input);
        $result = service('Voucher')->all();
        return response()->result(VoucherItemResource::collection($result), 'Voucher was updated');
    }

    public function remove(Request $request, Voucher $voucher)
    {
        $result = service('Voucher')->remove($voucher);
        $result = service('Voucher')->all();
        return response()->result(VoucherItemResource::collection($result), 'Voucher was removed');
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        $result = service('Voucher')->upload($file);
        return response()->result($result);
    }

    public function check(Request $request, $code)
    {
        $result = service('Voucher')->check($code);
        return response()->result($result);
    }

    public function get(Request $request, Voucher $voucher)
    {
        return response()->result(new VoucherItemResource($voucher));
    }

    public function users(Request $request)
    {
        $result = service('Voucher')->users();
        return response()->result($result);
    }
}
