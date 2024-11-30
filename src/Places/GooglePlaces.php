<?php

namespace SKAgarwal\GoogleApi\Places;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\QueryAuthenticator;
use Saloon\Http\Response;
use SKAgarwal\GoogleApi\Connector;
use SKAgarwal\GoogleApi\Places\Requests\FindPlace;
use SKAgarwal\GoogleApi\Places\Requests\NearbySearch;
use SKAgarwal\GoogleApi\Places\Requests\Photo;
use SKAgarwal\GoogleApi\Places\Requests\PlaceAutocomplete;
use SKAgarwal\GoogleApi\Places\Requests\PlaceDetails;
use SKAgarwal\GoogleApi\Places\Requests\QueryAutocomplete;
use SKAgarwal\GoogleApi\Places\Requests\TextSearch;

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
     * @return \Saloon\Contracts\Authenticator|null
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new QueryAuthenticator('key', $this->key);
    }

    /**
     * @param  string  $input
     * @param  string  $inputType
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function findPlace(string $input, string $inputType, array $params = []): Response
    {
        return $this->send(new FindPlace($input, $inputType, $params));
    }

    /**
     * @param  string  $location
     * @param  string|null  $radius
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Saloon\Http\Response
     */
    public function nearbySearch(string $location, ?string $radius = null, array $params = []): Response
    {
        return $this->send(new NearbySearch($location, $radius, $params));
    }

    /**
     * @param  string  $input
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function placeAutocomplete(string $input, array $params = []): Response
    {
        return $this->send(new PlaceAutocomplete($input, $params));
    }

    /**
     * @param  string  $placeId
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function placeDetails(string $placeId, array $params = []): Response
    {
        return $this->send(new PlaceDetails($placeId, $params));
    }

    /**
     * @param  string  $input
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function queryAutocomplete(string $input, array $params = []): Response
    {
        return $this->send(new QueryAutocomplete($input, $params));
    }

    /**
     * @param  string  $query
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function textSearch(string $query, array $params = []): Response
    {
        return $this->send(new TextSearch($query, $params));
    }

    /**
     * @param  string  $photoReference
     * @param  array  $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return string|null
     */
    public function photo(string $photoReference, array $params = []): ?string
    {
        return $this->send(new Photo($photoReference, $params))->getRequest()->getPhotoUrl();
    }
}
