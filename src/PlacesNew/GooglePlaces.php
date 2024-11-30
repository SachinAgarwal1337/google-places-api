<?php

namespace SKAgarwal\GoogleApi\PlacesNew;

use Saloon\Http\Response;
use SKAgarwal\GoogleApi\Connector;
use SKAgarwal\GoogleApi\PlacesNew\Requests\Autocomplete;
use SKAgarwal\GoogleApi\PlacesNew\Requests\NearbySearch;
use SKAgarwal\GoogleApi\PlacesNew\Requests\PlaceDetails;
use SKAgarwal\GoogleApi\PlacesNew\Requests\PlacePhoto;
use SKAgarwal\GoogleApi\PlacesNew\Requests\TextSearch;

class GooglePlaces extends Connector
{
    /**
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return Endpoint::BASE_URL->value;
    }

    /**
     * @param  string  $input
     * @param  bool  $includeQueryPredictions
     * @param  array|null  $fields
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function autocomplete(
        string $input,
        bool $includeQueryPredictions = false,
        ?array $fields = null,
        array $params = [],
    ): Response {
        return $this->send(new Autocomplete($input, $includeQueryPredictions, $fields, $params));
    }

    /**
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  float  $radius
     * @param  array  $fields
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function nearbySearch(
        float $latitude,
        float $longitude,
        float $radius = 0.0,
        array $fields = ['*'],
        array $params = [],
    ): Response {
        return $this->send(new NearbySearch($latitude, $longitude, $radius, $fields, $params));
    }

    /**
     * @param  string  $placeId
     * @param  array  $fields
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function placeDetails(string $placeId, array $fields = ['*'], array $params = []): Response
    {
        return $this->send(new PlaceDetails($placeId, $fields, $params));
    }

    /**
     * @param  string  $name
     * @param  int|null  $maxHeightPx
     * @param  int|null  $maxWidthPx
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function placePhoto(string $name, int $maxHeightPx = null, int $maxWidthPx = null): Response
    {
        return $this->send(new PlacePhoto($name, $maxHeightPx, $maxWidthPx));
    }

    /**
     * @param  string  $textQuery
     * @param  array  $fields
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function textSearch(string $textQuery, array $fields = ['*'], array $params = []): Response
    {
        return $this->send(new TextSearch($textQuery, $fields, $params));
    }
}
