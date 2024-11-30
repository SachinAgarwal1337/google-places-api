<?php

namespace SKAgarwal\GoogleApi;

use Saloon\Contracts\Authenticator;
use Saloon\Enums\PipeOrder;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector as SaloonConnector;
use Saloon\Http\PendingRequest;
use Saloon\Http\Response;
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
     * @var bool
     */
    protected bool $throwOnErrors = false;

    /**
     * @param  string|null  $key
     * @param  bool|null  $verifySSL
     * @param  bool|null  $throwOnErrors
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function __construct(?string $key = null, ?bool $verifySSL = null, ?bool $throwOnErrors = null)
    {
        $errorMessage = 'Google Places API KEY is missing.';

        if (function_exists('config')) {
            $key = $key ?? config('google.places.key');
            $verifySSL = $verifySSL ?? config('google.places.verify_ssl');
            $throwOnErrors = $throwOnErrors ?? config('google.throw_on_errors', false);
            $errorMessage = 'Google Places API KEY is not set in google config file.';
        }

        if (!$key) {
            throw new GooglePlacesApiException($errorMessage);
        }

        $this->key = $key;
        $this->verifySSL = $verifySSL ?? true;
        $this->throwOnErrors = $throwOnErrors ?? false;
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        if ($this->throwOnErrors) {
            $pendingRequest->middleware()->onResponse(
                callable: static fn (Response $response) => $response->throw(),
                name: 'alwaysThrowOnErrors',
                order: PipeOrder::LAST,
            );
        }
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

    public function throwOnErrors(bool $throwOnErrors): static
    {
        $this->throwOnErrors = $throwOnErrors;

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

    /**
     * @return array|mixed[]
     */
    protected function defaultHeaders(): array
    {
        if (function_exists('config')) {
            return config('google.headers', []);
        }

        return [];
    }
}
