<?php

namespace SKAgarwal\GoogleApi\PlacesNew\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use SKAgarwal\GoogleApi\PlacesNew\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/nearby-search
 */
class NearbySearch extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::POST;

    /**
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  float  $radius
     * @param  array  $fields
     * @param  array  $params
     */
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly float $radius = 0.0,
        private readonly array $fields = ['*'],
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
            'fields' => implode(',', $this->fields),
        ];
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultBody(): array
    {
        return [
            'locationRestriction' => [
                'circle' => [
                    'center' => [
                        'latitude' => $this->latitude,
                        'longitude' => $this->longitude,
                    ],
                    'radius' => $this->radius,
                ],
            ],
            ...$this->params,
        ];
    }
}
