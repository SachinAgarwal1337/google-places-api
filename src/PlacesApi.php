<?php
namespace SkAgarwal\GoogleApi;

class PlacesApi
{
    /**
     * @var null
     */
    private $key;

    /**
     * PlacesApi constructor.
     *
     * @param null $key
     */
    public function __construct($key = null)
    {
        $this->key = $key;
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
}
