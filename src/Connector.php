<?php

namespace SKAgarwal\GoogleApi;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector as SaloonConnector;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

abstract class Connector extends SaloonConnector
{
    /**
     * @var string|null
     */
    protected ?string $key;

    /**
     * @var bool
     */
    protected bool $verifySSL = true;

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
}
