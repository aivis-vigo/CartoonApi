<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;

class Cache
{
    public static function save(string $key, string $data, int $ttl = 120): void
    {
        $cacheFile = '../Cache/' . $key; // ../Cache/Character - example

        file_put_contents($cacheFile, json_encode([
            'expires_at' => Carbon::now()->addSeconds($ttl)->toTimeString(),
            'content' => $data
        ]));
    }

    public static function delete(string $key): void
    {
        unlink('../Cache/' . $key);
    }

    public static function get(string $key): ?string
    {
        if (!self::has($key)) {
            return null;
        }

        $content = json_decode(file_get_contents('../Cache/' . $key));

        return $content->content;
    }

    public static function has(string $key): bool
    {
        if (!file_exists('../Cache/' . $key)) {
            return false;
        }

        $content = json_decode(file_get_contents('../Cache/' . $key));

        return Carbon::parse()->gt($content->expires_at);
    }
}