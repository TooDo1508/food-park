<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGatewaySetting;
use App\Services\PaymentGatewaySettingService;
use App\Traits\FileUploadTrait;

class PaymentGatewaySettingController extends Controller
{
    use FileUploadTrait;

    public function index(){
        $paypalSetting = PaymentGatewaySetting::pluck('value', 'key');
        return view('admin.payment-setting.index', compact('paypalSetting'));
    }

    public function paypalSettingUpdate(Request $request){
        $validateData = $request->validate([
            'paypal_status' => ['required', 'boolean'],
            'paypal_acount_mode' => ['required', 'in:sandbox,live'],
            'paypal_country' => ['required'],
            'paypal_currency' => ['required'],
            'paypal_rate' => ['required', 'numeric'],
            'paypal_api_key' => ['required'],
            'paypal_secret_key' => ['required'],
        ]);

        if($request->hasFile('paypal_logo')){
            $request->validate([
                'paypal_logo' => ['nullable', 'image'],
            ]);

            $imagePath = $this->uploadImage($request, 'paypal_logo');
            PaymentGatewaySetting::updateOrCreate(
                ['key' => 'paypal_logo'],
                ['value' => $imagePath]
            );
        }

        foreach($validateData as $key => $value){
            PaymentGatewaySetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        };

        $paymentGatewaySettingService = app(PaymentGatewaySettingService::class);
        $paymentGatewaySettingService->clearCacheSettings();

        toastr()->success('Update successfully!');

        return redirect()->back();
    }
}
