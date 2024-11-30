<?php

namespace SKAgarwal\GoogleApi\PlacesNew;

use Illuminate\Support\Str;

enum Endpoint: string
{
    case BASE_URL = 'https://places.googleapis.com/v1';

    case TEXT_SEARCH = 'places:searchText';

    case NEARBY_SEARCH = 'places:searchNearby';

    case PLACE_DETAILS = 'places/{placeId}';

    case PLACE_PHOTO = '{name}/media';

    case AUTOCOMPLETE = 'places:autocomplete';

    /**
     * @param  string  $placeId
     *
     * @return string
     */
    public static function placeDetails(string $placeId): string
    {
        return Str::of(self::PLACE_DETAILS->value)->replace('{placeId}', $placeId)->toString();
    }

    /**
     * @param  string  $name
     *
     * @return string
     */
    public static function placePhoto(string $name): string
    {
        return Str::of(self::PLACE_PHOTO->value)->replace('{name}', $name)->toString();
    }
}
