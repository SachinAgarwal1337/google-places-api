<?php

namespace SKAgarwal\GoogleApi\PlacesNew\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\PlacesNew\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/place-details
 */
class PlaceDetails extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $paceId
     * @param  array  $fields
     * @param  array  $params
     */
    public function __construct(
        private readonly string $paceId,
        private readonly array $fields = ['*'],
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::placeDetails($this->paceId);
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'fields' => implode(',', $this->fields),
            ...$this->params,
        ];
    }
}
