<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\Places\Endpoint;

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
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function __construct(
        private readonly string $location,
        private ?string $radius = null,
        private array $params = [],
    ) {
        $this->params['radius'] = $radius;

        if (array_key_exists('rankby', $this->params) and $this->params['rankby'] === 'distance') {
            unset($this->params['radius']);

            if (!array_any_keys_exists(['keyword', 'name', 'type'], $this->params)) {
                throw new GooglePlacesApiException("Nearby Search require one"
                    . " or more of 'keyword', 'name', or 'type' params since 'rankby' = 'distance'.");
            }
        } elseif (!$this->radius) {
            throw new GooglePlacesApiException("'radius' param is not defined.");
        }
    }

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
