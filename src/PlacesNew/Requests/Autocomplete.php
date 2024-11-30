<?php

namespace SKAgarwal\GoogleApi\PlacesNew\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use SKAgarwal\GoogleApi\PlacesNew\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/place-autocomplete
 */
class Autocomplete extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::POST;

    /**
     * @param  string  $input
     * @param  array|null  $fields
     * @param  bool  $includeQueryPredictions
     * @param  array  $params
     */
    public function __construct(
        private readonly string $input,
        private readonly bool $includeQueryPredictions = false,
        private readonly ?array $fields = null,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::AUTOCOMPLETE->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        if (is_null($this->fields)) {
            return [];
        }

        return [
            'fields' => implode(',', $this->fields),
        ];
    }

    /**
     * @return array
     */
    protected function defaultBody(): array
    {
        return [
            'input' => $this->input,
            'includeQueryPredictions' => $this->includeQueryPredictions,
            ...$this->params,
        ];
    }
}
