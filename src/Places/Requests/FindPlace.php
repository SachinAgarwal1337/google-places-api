<?php

namespace SKAgarwal\GoogleApi\Places\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SKAgarwal\GoogleApi\Places\Endpoint;

/**
 * @see https://developers.google.com/maps/documentation/places/web-service/search-find-place
 */
class FindPlace extends Request
{
    /**
     * @var \Saloon\Enums\Method
     */
    protected Method $method = Method::GET;

    /**
     * @param  string  $input
     * @param  string  $inputType
     * @param  array  $params
     */
    public function __construct(
        private readonly string $input,
        private readonly string $inputType,
        private readonly array $params = [],
    ) {}

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return Endpoint::FIND_PLACE->value;
    }

    /**
     * @return array|mixed[]
     */
    protected function defaultQuery(): array
    {
        return [
            'input' => $this->input,
            'inputtype' => $this->inputType,
            ...$this->params,
        ];
    }
}
