<?php

namespace SKAgarwal\GoogleApi\GoogleMaps;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\QueryAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\FindPlace;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\NearbySearch;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\PlaceAutocomplete;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\PlaceDetails;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\QueryAutocomplete;
use SKAgarwal\GoogleApi\GoogleMaps\Requests\TextSearch;

class GoogleMaps extends Connector
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
        return Endpoint::BASE->value;
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
     * @throws \Saloon\Exceptions\Request\RequestException
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
}
