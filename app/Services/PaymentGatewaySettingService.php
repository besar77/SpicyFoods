<?php


namespace App\Services;

use App\Models\PaymentGatewaySetting;
use Cache;

class PaymentGatewaySettingService
{

    public function getSettings()
    {
        return Cache::rememberForever('gatewaySettings', function () {
            return PaymentGatewaySetting::pluck('value', 'key')     //['key'=>'value'] - ne ksi format na duhen datat
                ->toArray();
        });
    }

    public function setGlobalSettings(): void
    {
        $settings = $this->getSettings();
        config()->set('gatewaySettings', $settings);
    }

    public function clearCachedSettings(): void
    {
        Cache::forget('gatewaySettings');
    }
}
