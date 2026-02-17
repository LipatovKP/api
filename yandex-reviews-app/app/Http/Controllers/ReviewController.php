<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\YandexBusinessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private readonly YandexBusinessService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'in:newest,oldest'],
        ]);

        $setting = Setting::where('user_id', $request->user()->id)->first();

        if (!$setting?->organization_id) {
            return response()->json([
                'message' => 'Сначала заполните настройки интеграции.',
            ], 422);
        }

        $payload = $this->service->getOrganizationReviews(
            organizationId: $setting->organization_id,
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', (int) config('services.yandex_business.per_page', 20)),
            sort: $request->string('sort', 'newest')->value(),
        );

        return response()->json($payload);
    }
}
