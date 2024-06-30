<?php

namespace SKAgarwal\GoogleApi\GoogleMaps;

enum Endpoint: string
{
    case BASE = 'https://maps.googleapis.com/maps/api/place/';

    case NEARBY_SEARCH = 'nearbysearch/json';

    case TEXT_SEARCH = 'textsearch/json';

    case FIND_PLACE = 'findplacefromtext/json';

    case PLACE_DETAILS = 'details/json';

    case PLACE_AUTOCOMPLETE = 'autocomplete/json';

    case QUERY_AUTOCOMPLETE = 'queryautocomplete/json';

    case PLACE_ADD = 'add/json';

    case PLACE_DELETE = 'delete/json';

    case PLACE_PHOTO = 'photo';
}
