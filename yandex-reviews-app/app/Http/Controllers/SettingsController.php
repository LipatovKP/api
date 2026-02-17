<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $setting = Setting::firstOrNew([
            'user_id' => $request->user()->id,
        ]);

        return response()->json($setting);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'map_url' => ['required', 'url'],
            'organization_id' => ['required', 'string', 'max:255'],
            'business_id' => ['nullable', 'string', 'max:255'],
        ]);

        $setting = Setting::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data,
        );

        return response()->json([
            'message' => 'Настройки сохранены',
            'setting' => $setting,
        ]);
    }
}
