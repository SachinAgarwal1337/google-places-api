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
];
