<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use GuzzleHttp\TransferStats;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\Places\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/photos
 */
class Photo extends Request
{
    protected Method $method = Method::GET;

    protected ?string $photoUrl = null;

    public function __construct(
        private readonly string $photoReference,
        private readonly array $params = [],
    ) {
        if (!array_any_keys_exists(['maxwidth', 'maxheight'], $this->params)) {
            throw new GooglePlacesApiException('maxwidth or maxheight param is required');
        }
    }

    protected function defaultConfig(): array
    {
        return [
            'on_stats' => function (TransferStats $stats) {
                $this->photoUrl = $stats->getEffectiveUri();
            },
        ];
    }

    public function resolveEndpoint(): string
    {
        return Endpoint::PLACE_PHOTO->value;
    }

    protected function defaultQuery(): array
    {
        return [
            'photo_reference' => $this->photoReference,
            ...$this->params,
        ];
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }
}
