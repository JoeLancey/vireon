<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Coupon not found.'], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Coupon is invalid or expired.'], 400);
        }

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'min_order_amount' => $coupon->min_order_amount,
        ]);
    }
}
