<?php

namespace SKAgarwal\GoogleApi;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Collection;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\Exceptions\InvalidRequestException;
use SKAgarwal\GoogleApi\Exceptions\NotImplementedException;
use SKAgarwal\GoogleApi\Exceptions\OverQueryLimitException;
use SKAgarwal\GoogleApi\Exceptions\RequestDeniedException;
use SKAgarwal\GoogleApi\Exceptions\UnknownErrorException;

/**
 * @deprecated Use SKAgarwal\GoogleApi\Places\GooglePlaces instead.
 */
class PlacesApi
{
    public const BASE_URL = 'https://maps.googleapis.com/maps/api/place/';
    
    public const NEARBY_SEARCH_URL = 'nearbysearch/json';
    
    public const TEXT_SEARCH_URL = 'textsearch/json';
    
    public const FIND_PLACE = 'findplacefromtext/json';
    
    public const DETAILS_SEARCH_URL = 'details/json';
    
    public const PLACE_AUTOCOMPLETE_URL = 'autocomplete/json';
    
    public const QUERY_AUTOCOMPLETE_URL = 'queryautocomplete/json';
    
    public const PLACE_ADD_URL = 'add/json';
    
    public const PLACE_DELETE_URL = 'delete/json';
    
    public const PLACE_PHOTO_URL = 'photo';
    
    /**
     * @var
     */
    public $status;
    
    /**
     * @var null|string
     */
    private $key;
    
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    
    /**
     * @var bool
     */
    private $verifySSL;
    
    /**
     * @var array
     */
    private $headers = [];
    
    /**
     * PlacesApi constructor.
     *
     * @param string|null $key
     * @param bool $verifySSL
     * @param array $headers
     */
    public function __construct(?string $key = null, bool $verifySSL = true, array $headers = [])
    {
        $this->key = $key;
        
        $this->verifySSL = $verifySSL;
        
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => $headers,
        ]);
    }
    
    /**
     * Find Place Request to google places api.
     *
     * @param string $input (for example, a name, address, or phone number)
     * @param string $inputType (textquery or phonenumber)
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function findPlace(string $input, string $inputType, array $params = []): Collection
    {
        $this->checkKey();
        
        $params['input'] = $input;
        
        $params['inputtype'] = $inputType;
        
        $response = $this->makeRequest(self::FIND_PLACE, $params);
        
        return $this->convertToCollection($response, 'candidates');
    }
    
    /**
     * Place Nearby Search Request to google api.
     *
     * @param string $location
     * @param string|null $radius
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function nearbySearch(string $location, ?string $radius = null, array $params = []): Collection
    {
        $this->checkKey();
        
        $params = $this->prepareNearbySearchParams($location, $radius, $params);
        $response = $this->makeRequest(self::NEARBY_SEARCH_URL, $params);
        
        return $this->convertToCollection($response, 'results');
    }
    
    /**
     * Place Text Search Request to google places api.
     *
     * @param string $query
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function textSearch(string $query, array $params = []): Collection
    {
        $this->checkKey();
        
        $params['query'] = $query;
        $response = $this->makeRequest(self::TEXT_SEARCH_URL, $params);
        
        return $this->convertToCollection($response, 'results');
    }
    
    /**
     * Place Details Request to google places api.
     *
     * @param string $placeId
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function placeDetails(string $placeId, array $params = []): Collection
    {
        $this->checkKey();
        
        $params['placeid'] = $placeId;
        
        $response = $this->makeRequest(self::DETAILS_SEARCH_URL, $params);
        
        return $this->convertToCollection($response);
    }

    /**
     * @param  string  $photoReference
     * @param  array  $params
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return string
     */
    public function photo(string $photoReference, array $params = []): string
    {
        $this->checkKey();
        
        $params['photoreference'] = $photoReference;
        
        if (!array_any_keys_exists(['maxwidth', 'maxheight'], $params)) {
            throw new GooglePlacesApiException('maxwidth or maxheight param is required');
        }
        
        $options = $this->getOptions($params);
        
        $url = '';
        
        $options['on_stats'] = function (TransferStats $stats) use (&$url) {
            $url = $stats->getEffectiveUri();
        };
        
        $this->client->get(self::PLACE_PHOTO_URL, $options);
        
        return (string)$url;
    }
    
    /**
     * Place AutoComplete Request to google places api.
     *
     * @param string $input
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function placeAutocomplete(string $input, array $params = []): Collection
    {
        $this->checkKey();
        
        $params['input'] = $input;
        
        $response = $this->makeRequest(self::PLACE_AUTOCOMPLETE_URL, $params);
        
        return $this->convertToCollection($response, 'predictions');
    }
    
    /**
     * Query AutoComplete Request to the Google api.
     *
     * @param string $input
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return \Illuminate\Support\Collection
     */
    public function queryAutocomplete(string $input, array $params = []): Collection
    {
        $this->checkKey();
        
        $params['input'] = $input;
        
        $response = $this->makeRequest(self::QUERY_AUTOCOMPLETE_URL, $params);
        
        return $this->convertToCollection($response, 'predictions');
    }
    
    /**
     * @param string $uri
     * @param array $params
     * @param string $method
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\InvalidRequestException
     * @throws \SKAgarwal\GoogleApi\Exceptions\NotImplementedException
     * @throws \SKAgarwal\GoogleApi\Exceptions\OverQueryLimitException
     * @throws \SKAgarwal\GoogleApi\Exceptions\RequestDeniedException
     * @throws \SKAgarwal\GoogleApi\Exceptions\UnknownErrorException
     * @return mixed|string
     */
    private function makeRequest(string $uri, array $params, string $method = 'get')
    {
        $options = $this->getOptions($params, $method);
        
        $response = json_decode(
            $this->client->$method($uri, $options)->getBody()->getContents(),
            true
        );
        
        $this->setStatus($response['status']);
        
        switch ($response['status']) {
            case 'OK':
            case 'ZERO_RESULTS':
                return $response;
            case 'INVALID_REQUEST':
                throw new InvalidRequestException(
                    "Response returned with status: " . $response['status'],
                    $response['error_message'] ?? null
                );
            case 'OVER_QUERY_LIMIT':
                throw new OverQueryLimitException(
                    "Response returned with status: " . $response['status'],
                    $response['error_message'] ?? null
                );
            case 'REQUEST_DENIED':
                throw new RequestDeniedException(
                    "Response returned with status: " . $response['status'],
                    $response['error_message'] ?? null
                );
            case 'UNKNOWN_ERROR':
                throw new UnknownErrorException(
                    "Response returned with status: " . $response['status'],
                    $response['error_message'] ?? null
                );
            default:
                throw new NotImplementedException(
                    "Response returned with status: " . $response['status'],
                    $response['error_message'] ?? null
                );
        }
    }
    
    /**
     * @param array $data
     * @param null $index
     *
     * @return \Illuminate\Support\Collection
     */
    private function convertToCollection(array $data, $index = null): Collection
    {
        $data = collect($data);
        
        if ($index) {
            $data[$index] = collect($data[$index]);
        }
        
        return $data;
    }
    
    /**
     * @param mixed $status
     */
    private function setStatus($status)
    {
        $this->status = $status;
    }
    
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getKey(): string|null
    {
        return $this->key;
    }
    
    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): PlacesApi
    {
        $this->key = $key;
        
        return $this;
    }
    
    /**
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function checkKey()
    {
        if (!$this->key) {
            throw new GooglePlacesApiException('API KEY is not specified.');
        }
    }
    
    /**
     * Prepare the params for the Place Search.
     *
     * @param string $location
     * @param string $radius
     * @param array $params
     *
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     * @return mixed
     */
    private function prepareNearbySearchParams(string $location, string $radius, array $params): array
    {
        $params['location'] = $location;
        $params['radius'] = $radius;
        
        if (array_key_exists('rankby', $params)
            and $params['rankby'] === 'distance'
        ) {
            unset($params['radius']);
            
            if (!array_any_keys_exists(['keyword', 'name', 'type'], $params)) {
                throw new GooglePlacesApiException("Nearby Search require one"
                    . " or more of 'keyword', 'name', or 'type' params since 'rankby' = 'distance'.");
            }
        } elseif (!$radius) {
            throw new GooglePlacesApiException("'radius' param is not defined.");
        }
        
        return $params;
    }
    
    /**
     * @param bool $verifySSL
     *
     * @return PlacesApi
     */
    public function verifySSL(bool $verifySSL = true): PlacesApi
    {
        $this->verifySSL = $verifySSL;
        
        return $this;
    }
    
    /**
     * @param array $params
     * @param string $method
     *
     * @return array
     */
    private function getOptions(array $params, string $method = 'get'): array
    {
        $options = [
            'query' => [
                'key' => $this->key,
            ],
        ];
        
        if ($method == 'post') {
            $options = array_merge(['body' => json_encode($params)], $options);
        } else {
            $options['query'] = array_merge($options['query'], $params);
        }
        
        $options['http_errors'] = false;
        
        $options['verify'] = $this->verifySSL;
        
        if (!empty($this->headers)) {
            $options['headers'] = $this->headers;
        }
        
        return $options;
    }
    
    /**
     * @param array $headers
     *
     * @return PlacesApi
     */
    public function withHeaders(array $headers): PlacesApi
    {
        $this->headers = $headers;
        
        return $this;
    }
}
