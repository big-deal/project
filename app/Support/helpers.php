<?php

if (!function_exists('debug_blacklist')) {
    function debug_blacklist($secrets, $key = null): array
    {
        $superGlobalNames = $key ?? [
                '_GET',
                '_POST',
                '_FILES',
                '_COOKIE',
                '_SESSION',
                '_SERVER',
                '_ENV',
            ];
        $blacklist = [];

        foreach ((array)$superGlobalNames as $superGlobalName) {
            foreach ((array)$secrets as $secret) {
                $blacklist[$superGlobalName][] = $secret;
            }
        }

        return $blacklist;
    }
}
