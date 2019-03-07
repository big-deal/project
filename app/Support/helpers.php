<?php

if (!function_exists('debug_blacklist')) {
    /**
     * @param array|string $secrets
     * @return array
     */
    function debug_blacklist($secrets): array
    {
        $superGlobalNames = [
            '_GET',
            '_POST',
            '_FILES',
            '_COOKIE',
            '_SESSION',
            '_SERVER',
            '_ENV',
        ];
        $blacklist = [];

        foreach ($superGlobalNames as $key) {
            foreach ((array)$secrets as $secret) {
                $blacklist[$key][] = $secret;
            }
        }

        return $blacklist;
    }
}
