<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;

class YandexBusinessService
{
    public function __construct(private readonly HttpFactory $http)
    {
    }

    public function getOrganizationReviews(string $organizationId, int $page = 1, int $perPage = 20, string $sort = 'newest'): array
    {
        $orderBy = $sort === 'oldest' ? 'created_asc' : 'created_desc';

        $response = $this->http->withToken(config('services.yandex_business.token'))
            ->acceptJson()
            ->baseUrl(config('services.yandex_business.base_url'))
            ->get("/v1/organizations/{$organizationId}/reviews", [
                'page' => $page,
                'limit' => $perPage,
                'orderBy' => $orderBy,
            ])
            ->throw()
            ->json();

        return [
            'company' => [
                'rating' => Arr::get($response, 'company.rating'),
                'reviews_count' => Arr::get($response, 'company.reviewsCount'),
                'name' => Arr::get($response, 'company.name'),
            ],
            'reviews' => Arr::get($response, 'reviews', []),
            'pagination' => [
                'current_page' => Arr::get($response, 'pagination.page', $page),
                'per_page' => Arr::get($response, 'pagination.limit', $perPage),
                'total' => Arr::get($response, 'pagination.total', 0),
                'last_page' => (int) ceil(max(1, Arr::get($response, 'pagination.total', 0)) / max(1, Arr::get($response, 'pagination.limit', $perPage))),
            ],
        ];
    }
}
