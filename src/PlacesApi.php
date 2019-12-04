<?php

namespace SKAgarwal\GoogleApi;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\Exceptions\InvalidRequestException;
use SKAgarwal\GoogleApi\Exceptions\NotImplementedException;
use SKAgarwal\GoogleApi\Exceptions\OverQueryLimitException;
use SKAgarwal\GoogleApi\Exceptions\RequestDeniedException;
use SKAgarwal\GoogleApi\Exceptions\UnknownErrorException;

class PlacesApi
{
    const BASE_URL = 'https://maps.googleapis.com/maps/api/place/';
    
    const NEARBY_SEARCH_URL = 'nearbysearch/json';
    
    const TEXT_SEARCH_URL = 'textsearch/json';
    
    const FIND_PLACE = 'findplacefromtext/json';
    
    const DETAILS_SEARCH_URL = 'details/json';
    
    const PLACE_AUTOCOMPLETE_URL = 'autocomplete/json';
    
    const QUERY_AUTOCOMPLETE_URL = 'queryautocomplete/json';
    
    const PLACE_ADD_URL = 'add/json';
    
    const PLACE_DELETE_URL = 'delete/json';
    
    const PLACE_PHOTO_URL = 'photo';
    
    /**
     * @var
     */
    public $status;
    
    /**
     * @var null
     */
    private $key = null;
    
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    
    /**
     * @var bool
     */
    private $verifySSL = true;
    
    /**
     * @var array
     */
    private $headers = [];
    
    /**
     * PlacesApi constructor.
     *
     * @param null $key
     * @param bool $verifySSL
     */
    public function __construct($key = null, $verifySSL = true, array $headers = [])
    {
        $this->key = $key;
        
        $this->verifySSL = $verifySSL;
        
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers'  => $headers,
        ]);
    }
    
    /**
     * Find Place Request to google places api.
     *
     * @param string $input (for example, a name, address, or phone number)
     * @param string $inputType (textquery or phonenumber)
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function findPlace($input, $inputType, $params = [])
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
     * @param $location
     * @param null $radius
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function nearbySearch($location, $radius = null, $params = [])
    {
        $this->checkKey();
        
        $params = $this->prepareNearbySearchParams($location, $radius, $params);
        $response = $this->makeRequest(self::NEARBY_SEARCH_URL, $params);
        
        return $this->convertToCollection($response, 'results');
    }
    
    /**
     * Place Text Search Request to google places api.
     *
     * @param $query
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function textSearch($query, $params = [])
    {
        $this->checkKey();
        
        $params['query'] = $query;
        $response = $this->makeRequest(self::TEXT_SEARCH_URL, $params);
        
        return $this->convertToCollection($response, 'results');
        
    }
    
    /**
     * Place Details Request to google places api.
     *
     * @param $placeId
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function placeDetails($placeId, $params = [])
    {
        $this->checkKey();
        
        $params['placeid'] = $placeId;
        
        $response = $this->makeRequest(self::DETAILS_SEARCH_URL, $params);
        
        return $this->convertToCollection($response);
    }
    
    /**
     * @param $photoReference
     * @param array $params
     *
     * @return mixed|string
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function photo($photoReference, $params = [])
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
     * @param $input
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function placeAutocomplete($input, $params = [])
    {
        $this->checkKey();
        
        $params['input'] = $input;
        
        $response = $this->makeRequest(self::PLACE_AUTOCOMPLETE_URL, $params);
        
        return $this->convertToCollection($response, 'predictions');
    }
    
    /**
     * Query AutoComplete Request to the google api.
     *
     * @param $input
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function queryAutocomplete($input, $params = [])
    {
        $this->checkKey();
        
        $params['input'] = $input;
        
        $response = $this->makeRequest(self::QUERY_AUTOCOMPLETE_URL, $params);
        
        return $this->convertToCollection($response, 'predictions');
    }
    
    /**
     * @param $uri
     * @param $params
     * @param $method
     *
     * @return mixed|string
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function makeRequest($uri, $params, $method = 'get')
    {
        $options = $this->getOptions($params, $method);
        
        $response = json_decode(
            $this->client->$method($uri, $options)->getBody()->getContents(),
            true
        );
        
        $this->setStatus($response['status']);
        
        switch($response['status']){
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
    private function convertToCollection(array $data, $index = null)
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
     * @return null
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * @param null $key
     *
     * @return $this
     */
    public function setKey($key)
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
     * @param $location
     * @param $radius
     * @param $params
     *
     * @return mixed
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function prepareNearbySearchParams($location, $radius, $params)
    {
        $params['location'] = $location;
        $params['radius'] = $radius;
        
        if (array_key_exists('rankby', $params)
            AND $params['rankby'] === 'distance'
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
    public function verifySSL($verifySSL = true)
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
    private function getOptions($params, $method = 'get')
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
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;
        
        return $this;
    }
}
