<?php

return [
    'places' => [
        /**
         * Google Places API Key
         * @see https://developers.google.com/maps/documentation/places/web-service/get-api-key
         */
        'key' => env('GOOGLE_PLACES_API_KEY', null),


        'verify_ssl' => true,
    ],

    /**
     * Throw exceptions when Google API returns an error
     *
     * If set to false, Error message will be returned as response,
     * and you need check if the response has failed using the failed() method
     *
     * You can also use throw() method to throw an exception per-request basis
     */
    'throw_on_errors' => false,
];
