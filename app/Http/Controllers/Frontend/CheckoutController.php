<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $deliveryAreas = DeliveryArea::where('status', 1)->get();
        $addresses = Address::where('user_id', auth()->user()->id)->get();
        return view('frontend.pages.checkout', compact('addresses', 'deliveryAreas'));
    }

    public function calculateDeliveryCharge(string $id)
    {
        try {
            $address = Address::findOrFail($id);
            $deliveryFee = $address->deliveryArea->delivery_fee;
            $grandTotal = number_format(grandCartTotal() + $deliveryFee, 2);

            return response([
                'delivery_fee' => $deliveryFee,
                'grand_total' => $grandTotal,
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function checkoutRedirect(Request $request){
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $address = Address::with('deliveryArea')->findOrFail($request->id);

        $selectedAddress = $address->address.', Area: '. $address->deliveryArea?->area_name;

        // session()->put('address')

        session('address' , $selectedAddress);

        return response([
            'redirect_url' => route('payment.index')
        ]);
    }
}
