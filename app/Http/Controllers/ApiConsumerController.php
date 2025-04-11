<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApiConsumerController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        // Menggunakan konfigurasi dari file config/services.php
        $this->apiBaseUrl = config('services.api.base_url', 'https://lssoft88.xyz/api');
    }

    public function getCustomers()
    {
        try {
            return Cache::remember('customers', now()->addMinutes(10), function () {
                $response = Http::get("{$this->apiBaseUrl}/customers");

                if ($response->successful()) {
                    return $response->json();
                }

                return ['error' => 'Failed to fetch data', 'details' => $response->body()];
            });
        } catch (\Exception $e) {
            Log::error('API Request Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Service unavailable'], 503);
        }
    }

    // public function getCombinedCustomerNames()
    // {
    //     $data = $this->getCustomers();

    //     if (isset($data['error'])) {
    //         return [];
    //     }

    //     return collect($data)->map(function ($item) {
    //         $name = isset($item['name']) ? trim($item['name']) : '';
    //         $city = isset($item['text_kota']) ? trim($item['text_kota']) : '';
    //         return strtolower($name . ' ' . $city);
    //     })->toArray();
    // }

    public function getCustomerNameReferences()
    {
        $data = $this->getCustomers();

        if (isset($data['error'])) {
            return [
                'names' => [],
                'names_with_city' => []
            ];
        }

        return [
            'names' => collect($data)->pluck('name')->map(function ($n) {
                return strtolower(trim($n));
            })->toArray(),
            'names_with_city' => collect($data)->map(function ($item) {
                $name = trim($item['name'] ?? '');
                $city = trim($item['text_kota'] ?? '');
                return strtolower($name . ' ' . $city);
            })->toArray(),
        ];
    }


}