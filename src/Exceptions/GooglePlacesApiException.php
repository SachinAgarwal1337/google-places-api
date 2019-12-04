<?php
namespace SKAgarwal\GoogleApi\Exceptions;

class GooglePlacesApiException extends \Exception
{
    /**
     * @var mixed
     */
    private $error_message = null;

    /**
     * GooglePlacesApiException constructor.
     *
     * @param string $message
     * @param mixed $error_message
     */
    public function __construct ($message = "", $error_message = null )
    {
        parent::__construct($message);

        $this->error_message = $error_message;
    }

    /**
     * Get the error message
     *
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }
}
