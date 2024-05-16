<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class ConfigJson
{
    private static string $jsonPath = 'json/config.json';

    private static string $jsonTemplate = "{ 'waktu_masuk': '12:00' }";

    public static function modifyJson(array $json)
    {
        // Create json if json not available
        if (!Storage::exists(self::$jsonPath)) {
            Storage::put(self::$jsonPath, self::$jsonTemplate);
        }

        Storage::put(self::$jsonPath, json_encode($json));
    }

    public static function loadJson(): array
    {
        // Create json if json not available
        if (!Storage::exists(self::$jsonPath)) {
            Storage::put(self::$jsonPath, self::$jsonTemplate);
        }

        return json_decode(
            Storage::get(self::$jsonPath), true
        );
    }
}
