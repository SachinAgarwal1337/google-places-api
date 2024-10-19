<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Places\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/search-text
 */
class TextSearch extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $searchQuery
     * @param  array  $params
     */
    public function __construct(
        private readonly string $searchQuery,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::TEXT_SEARCH->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'query' => $this->searchQuery,
            ...$this->params,
        ];
    }
}
