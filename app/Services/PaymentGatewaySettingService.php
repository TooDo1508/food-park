<?php

namespace App\Services;

use App\Models\PaymentGatewaySetting;
use Cache;

class PaymentGatewaySettingService{
    function getSettings() {
        return Cache::rememberForever('gatewaySettings', function(){
            return PaymentGatewaySetting::pluck('value', 'key')->toArray();
        });
    }

    function setGlobalSettings(){
        $settings = $this->getSettings();
        config()->set('gatewaySettings', $settings);
    }

    function clearCacheSettings(){
        Cache::forget('gatewaySettings');
    }

}
