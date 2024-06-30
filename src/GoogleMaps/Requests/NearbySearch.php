<?php

namespace SKAgarwal\GoogleApi\GoogleMaps\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\GoogleMaps\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/search-nearby
 */
class NearbySearch extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $location
     * @param  string|null  $radius
     * @param  array  $params
     */
    public function __construct(
        private readonly string $location,
        private readonly ?string $radius = null,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::NEARBY_SEARCH->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'location' => $this->location,
            'radius' => $this->radius,
            ...$this->params,
        ];
    }
}
