<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Places\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/details
 */
class PlaceDetails extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $placeId
     * @param  array  $params
     */
    public function __construct(
        private readonly string $placeId,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::PLACE_DETAILS->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'place_id' => $this->placeId,
            ...$this->params,
        ];
    }
}
