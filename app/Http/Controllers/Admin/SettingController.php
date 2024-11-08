<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function updateGeneralSetting(Request $request)
    {

        $validatedData = $request->validate([
            'site_name' => ['required', 'max:255'],
            'site_default_currency' => ['required', 'max:8'],
            'site_currency_icon' => ['required', 'max:8'],
            'site_currency_icon_position' => ['required', 'max:255'],
        ]);

        foreach ($validatedData as $key => $value){
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value,]
            );
        }

        $settingsService = app(SettingsService::class);
        $settingsService->clearCacheSettings();
        toastr()->success('Update setting successfully.');

        return redirect()->back();
    }
}
