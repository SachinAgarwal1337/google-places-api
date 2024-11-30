<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Places\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/query
 */
class QueryAutocomplete extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $input
     * @param  array  $params
     */
    public function __construct(
        private readonly string $input,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::QUERY_AUTOCOMPLETE->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'input' => $this->input,
            ...$this->params,
        ];
    }
}
