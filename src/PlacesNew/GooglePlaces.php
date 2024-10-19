<?php

namespace SKAgarwal\GoogleApi\PlacesNew;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Auth\QueryAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesNew\Requests\Autocomplete;
use SKAgarwal\GoogleApi\PlacesNew\Requests\NearbySearch;
use SKAgarwal\GoogleApi\PlacesNew\Requests\PlaceDetails;
use SKAgarwal\GoogleApi\PlacesNew\Requests\PlacePhoto;
use SKAgarwal\GoogleApi\PlacesNew\Requests\TextSearch;

class GooglePlaces extends Connector
{
    /**
     * @var string|null
     */
    private ?string $key;

    /**
     * @var bool
     */
    private bool $verifySSL = true;

    /**
     * @param  string|null  $key
     * @param  bool|null  $verifySSL
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function __construct(?string $key = null, ?bool $verifySSL = null)
    {
        $errorMessage = 'Google Places API KEY is missing.';

        if (function_exists('config')) {
            $key = $key ?? config('google.places.key');
            $verifySSL = $verifySSL ?? config('google.places.verify_ssl');
            $errorMessage = 'Google Places API KEY is not set in google config file.';
        }

        if (!$key) {
            throw new GooglePlacesApiException($errorMessage);
        }

        $this->key = $key;
        $this->verifySSL = $verifySSL ?? true;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param  string  $key
     *
     * @return $this
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param  bool  $verifySSL
     *
     * @return static
     */
    public function verifySSL(bool $verifySSL = true): static
    {
        $this->verifySSL = $verifySSL;

        return $this;
    }

    /**
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return Endpoint::BASE_URL->value;
    }

    /**
     * @return bool[]
     */
    protected function defaultConfig(): array
    {
        return [
            'verify' => $this->verifySSL,
        ];
    }

    /**
     * @return \Saloon\Contracts\Authenticator|null
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new HeaderAuthenticator($this->key, 'X-Goog-Api-Key');
    }

    /**
     * @param  string  $input
     * @param  array  $fields
     * @param  bool  $includeQueryPredictions
     * @param  array  $params
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function autocomplete(
        string $input,
        array $fields = ['*'],
        bool $includeQueryPredictions = false,
        array $params = [],
    ): Response {
        return $this->send(new Autocomplete($input, $fields, $includeQueryPredictions, $params));
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
     * @param  bool  $skipHttpRedirect
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     * @return \Saloon\Http\Response
     */
    public function placePhoto(
        string $name,
        int $maxHeightPx = null,
        int $maxWidthPx = null,
        bool $skipHttpRedirect = false,
    ): Response {
        return $this->send(new PlacePhoto($name, $maxHeightPx, $maxWidthPx, $skipHttpRedirect));
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
