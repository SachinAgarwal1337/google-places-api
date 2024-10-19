<?php

namespace SKAgarwal\GoogleApi\PlacesNew\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesNew\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/place-photos
 */
class PlacePhoto extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $name
     * @param  int|null  $maxHeightPx
     * @param  int|null  $maxWidthPx
     * @param  bool  $skipHttpRedirect
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function __construct(
        private readonly string $name,
        private readonly ?int $maxHeightPx = null,
        private readonly ?int $maxWidthPx = null,
        private readonly bool $skipHttpRedirect = false,
    ) {
        if (!$this->maxHeightPx && !$this->maxWidthPx) {
            throw new GooglePlacesApiException('$maxHeightPx or $maxWidthPx param is required');
        }
    }

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::placePhoto($this->name);
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return array_filter([
            'maxHeightPx' => $this->maxHeightPx,
            'maxWidthPx' => $this->maxWidthPx,
            'skipHttpRedirect' => $this->skipHttpRedirect,
        ], fn ($value) => $value !== null);
    }
}
