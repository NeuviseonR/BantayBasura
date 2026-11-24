<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NominatimGeocodingService
{
    /**
     * Convert GPS coordinates into a human-readable place name using the
     * OpenStreetMap Nominatim service. Failures never prevent report submission.
     */
    public function reverseGeocode(float|string $latitude, float|string $longitude): ?string
    {
        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        if (!is_finite($latitude) || !is_finite($longitude)
            || $latitude < -90 || $latitude > 90
            || $longitude < -180 || $longitude > 180) {
            return null;
        }

        // Reuse successful lookups for the same approximate coordinates.
        $coordinateKey = sha1(sprintf('%.6f,%.6f', $latitude, $longitude));
        $cacheKey = 'nominatim:reverse:' . $coordinateKey;
        $cachedPlace = Cache::get($cacheKey);

        if (is_string($cachedPlace) && $cachedPlace !== '') {
            return $cachedPlace;
        }

        // Nominatim's public service policy allows no more than one request
        // per second. This shared cache lock also protects concurrent requests.
        if (!Cache::add('nominatim:request-lock', true, 2)) {
            return null;
        }

        try {
            $userAgent = (string) config('services.nominatim.user_agent');
            if ($userAgent === '') {
                $userAgent = 'BantayBasura/1.0';
            }

            $query = [
                'format' => 'jsonv2',
                'lat' => $latitude,
                'lon' => $longitude,
                'zoom' => 18,
                'addressdetails' => 1,
            ];

            $contactEmail = config('services.nominatim.email');
            if (is_string($contactEmail) && filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                $query['email'] = $contactEmail;
            }

            $response = Http::withHeaders([
                'User-Agent' => $userAgent,
                'Accept' => 'application/json',
            ])->timeout(6)->get('https://nominatim.openstreetmap.org/reverse', $query);

            if (!$response->successful()) {
                return null;
            }

            $place = $response->json('display_name');
            if (!is_string($place) || trim($place) === '') {
                return null;
            }

            $place = function_exists('mb_substr')
                ? mb_substr(trim($place), 0, 500)
                : substr(trim($place), 0, 500);
            Cache::put($cacheKey, $place, now()->addDays(30));

            return $place;
        } catch (\Throwable $exception) {
            // Geocoding is optional: network/API failures must not block reports.
            report($exception);

            return null;
        }
    }
}
