<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\JWTUserTrait;
use Illuminate\Support\Facades\Cache;

class ApiBaseController extends \App\Http\Controllers\Api\Controller
{

    /**
     * Extract token value from request
     *
     * @return string
     */
    protected function extractToken($request = false)
    {
        return JWTUserTrait::extractToken($request);
    }

    /**
     * Return User instance or false if not exist in DB
     *
     * @return mixed
     */
    protected function getUserInstance($request = false)
    {
        return JWTUserTrait::getUserInstance($request);
    }

    /**
     * Clear cache
     */
    protected function clearCache(): void //!important
    {
        Cache::flush();
    }

    /**
     * reset cache
     */
    protected function resetCache($keys = NULL): bool //!important
    {
        if (null === $keys) {
            Cache::flush();
        } elseif (is_array($keys)) {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        } else if (is_string($keys)) {
            Cache::forget($keys);
        }
        return true;

    }
}
