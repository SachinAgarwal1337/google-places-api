<?php

namespace SKAgarwal\GoogleApi\GoogleMaps\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\GoogleMaps\Endpoint;

/**
 * todo only use it in new one.
 *
 * @see https://developers.google.com/maps/documentation/places/web-service/photos
 */
class Photo extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $photoReference,
        private readonly array $params = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return Endpoint::PLACE_PHOTO->value;
    }

    protected function defaultQuery(): array
    {
        return [
            'photoreference' => $this->photoReference,
            ...$this->params,
        ];
    }
}
