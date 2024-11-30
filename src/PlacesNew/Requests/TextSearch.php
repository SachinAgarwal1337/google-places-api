<?php

namespace SKAgarwal\GoogleApi\PlacesNew\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use SKAgarwal\GoogleApi\PlacesNew\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/text-search
 */
class TextSearch extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::POST;

    /**
     * @param  string  $textQuery
     * @param  array  $fields
     * @param  array  $params
     */
    public function __construct(
        private readonly string $textQuery,
        private readonly array $fields = ['*'],
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
            'fields' => implode(',', $this->fields),
        ];
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultBody(): array
    {
        return [
            'textQuery' => $this->textQuery,
            ...$this->params,
        ];
    }
}
