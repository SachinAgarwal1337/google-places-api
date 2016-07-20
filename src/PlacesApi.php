<?php
namespace SKAgarwal\GoogleApi;

use GuzzleHttp\Client;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

class PlacesApi
{
    const NEARBY_SEARCH_URL = 'nearbysearch/json';

    const TEXT_SEARCH_URL = 'textsearch/json';

    const RADAR_SEARCH_URL = 'radarsearch/json';

    const DETAILS_SEARCH_URL = 'details/json';

    const PLACE_AUTOCOMPLETE_URL = 'autocomplete/json';

    const QUERY_AUTOCOMPLETE_URL = 'queryautocomplete/json';

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
     * PlacesApi constructor.
     *
     * @param null $key
     */
    public function __construct($key = null)
    {
        $this->key = $key;

        $this->client = new Client([
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/',
        ]);
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
     * Radar Search Request to google api
     *
     * @param $location
     * @param $radius
     * @param $params
     *
     * @return \Illuminate\Support\Collection
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function radarSearch($location, $radius, array $params)
    {
        $this->checkKey();

        $params = $this->prepareRadarSearchParams($location, $radius, $params);

        $response = $this->makeRequest(self::RADAR_SEARCH_URL, $params);

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
     * Place AutoComplete Request to google places api.
     *
     * @param $input
     * @param array $params
     *
     * @return \Illuminate\Support\Collection
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
     *
     * @return mixed|string
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function makeRequest($uri, $params)
    {
        $options = [
            'query' => [
                'key' => $this->key,
            ],
        ];

        $options['query'] = array_merge($options['query'], $params);

        $response = json_decode($this->client->get($uri, $options)
                                             ->getBody()->getContents(), true);

        $this->setStatus($response['status']);

        if ($response['status'] !== 'OK') {
            throw new GooglePlacesApiException("Response returned with status: "
                . $response['status']);
        }

        return $response;
    }

    /**
     * @param array $data
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
     * @param $location
     * @param $radius
     * @param $params
     *
     * @return mixed
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function prepareRadarSearchParams($location, $radius, $params)
    {
        $params['location'] = $location;
        $params['radius'] = $radius;

        if (!array_any_keys_exists(['keyword', 'name', 'type'], $params)) {
            throw new GooglePlacesApiException("Radar Search require one"
                . " or more of 'keyword', 'name', or 'type' params.");
        }

        return $params;
    }
}
