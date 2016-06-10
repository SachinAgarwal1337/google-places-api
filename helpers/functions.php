<?php

if (!function_exists('array_any_keys_exists')) {
    /**
     * Check if any 1 of the given keys exists.
     *
     * @param array $keys
     * @param array $array
     *
     * @return bool
     */
    function array_any_keys_exists(array $keys, array $array)
    {
        $result = false;

        foreach ($keys as $key) {
            $result = $result || array_key_exists($key, $array);
        }

        return $result;
    }
}
