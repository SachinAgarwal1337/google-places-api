<?php
namespace SKAgarwal\GoogleApi;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @deprecated
 */
class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'GooglePlaces';
    }
    
}
