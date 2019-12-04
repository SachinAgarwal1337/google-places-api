
[![Latest Stable Version](https://poser.pugx.org/skagarwal/google-places-api/v/stable?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![Latest Unstable Version](https://poser.pugx.org/skagarwal/google-places-api/v/unstable?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![Total Downloads](https://poser.pugx.org/skagarwal/google-places-api/downloads?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![License](https://poser.pugx.org/skagarwal/google-places-api/license?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)


# Google Places API.

This is a PHP wrapper for **Google Places API Web Service**. And is [Laravel Framework](https://laravel.com/docs/5.2) friendly.

## About Package
With just 2 lines of code you can request to any google places api feature. No need to manually perform any curl requests.

### The following place requests are available:
* [Place Search](#place-search) This service gives a list of places based on a user's location or search string.
* [Place Details](#place-details) This service gives more detailed information about a specific Place, including user reviews.
* [Place Autocomplete](#place-autocomplete) This service is Used to automatically fill in the name and/or address of a place as you type.
* [Query Autocomplete](#query-autocomplete) This service is Used to provide a query prediction service for text-based geographic searches, by returning suggested queries as you type.
* [Place Photo](#place-photo) This gives you access to the millions of photos stored in the Google's Places database
* [Custom Headers](#custom-headers) Set Custom Headers.
* [Additional Methods](#additional-methods) Additional Methods Available.

# Installation
Install it with composer
```
composer require skagarwal/google-places-api
```



# Usage

**Laravel user can see the [Laravel Usage](#laravel-usage) section**

## Step 1 - Import the class using namespace
```php
use SKAgarwal\GoogleApi\PlacesApi;
```

## Step 2 - Initiate the object
```php
$googlePlaces = new PlacesApi('API KEY');
```

**Note:** You can also set the **API KEY** after initiating the class using `setKey('KEY')` method. You can chain this with method with any other methods.

## Step 3 - Start Using the Api.
Example:
```php
$response = $googlePlaces->placeAutocomplete('some Place');
```

As mentioned earlier just 2 lines of code to make any request.

**Full example:**
```php
use SKAgarwal\GoogleApi\PlacesApi;


function () {
  $googlePlaces = new PlacesApi('API_KEY') # line 1
  $response = $googlePlaces->placeAutocomplete('some input'); # line 2
}

```

---

<a name=laravel-usage></a>
# Use with Laravel
## For Laravel 5.5
Auto Discovery added.

## For Laravel 5.4 and below
## Step 1
Set up the service provider and facade in the **config\app.php**
```php

'providers' => [
....
....
SKAgarwal\GoogleApi\ServiceProvider::class,
];

'aliases' => [
....
....
'GooglePlaces' => SKAgarwal\GoogleApi\Facade::class,
];

```

## Step 2
publish the config file with following artisan command
```
php artisan vendor:publish --provider="SKAgarwal\GoogleApi\ServiceProvider"
```

This will create **google.php** file in the config directory.

Set the *API KEY* in this config file.

## Set 3
Start using the package using Facade.

```
$response = GooglePlaces::placeAutocomplete('some city');
```

---
# Response
The response returned is a [Laravel's Collection](https://laravel.com/docs/5.2/collections) so that you can perform any of the available collection methods on it.

<blockquote>
If you are not familiar with <em>Laravel's Collection</em> you can either reference the docs <a href="https://laravel.com/docs/5.2/collections">here</a> or you can use <strong>response</strong> as simple array.
</blockquote>

---

# Available Methods

<a name=place-search></a>
## Place Search
### nearbySearch($location, $radius = null, $params = [])
* `location` — The latitude/longitude around which to retrieve place information. This must be specified as latitude, longitude.
* 'radius' — Defines the distance (in meters) within which to return place results. The maximum allowed radius is 50 000 meters. Note that `radius` must not be included if `rankby=distance` (described under **Optional parameters** below) is specified.
* If `rankby=distance` (described under **Optional parameters** below) is specified, then one or more of `keyword`, `name`, or `types` is required.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search)

### textSearch($query, $params = [])
* `query` — The text string on which to search, for example: "restaurant". The Google Places service will return candidate matches based on this string and order the results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search)

### findPlace($input, $inputType, $params = []) 
* `input` — The text input specifying which place to search for (for example, a name, address, or phone number).
* `inputType` — The type of input. This can be one of either textquery or phonenumber. Phone numbers must be in international format (prefixed by a plus sign ("+"), followed by the country code, then the phone number itself).
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search#FindPlaceRequests)

---

<a name=place-details></a>
# Place Details
### placeDetails($placeId, $params = [])
* `placeId` — A textual identifier that uniquely identifies a place, returned from a Place Search.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/details)

---

<a name=place-autocomplete></a>
# Place Autocomplete
### placeAutocomplete($input, $params = [])
* `input` — The text string on which to search. The Place Autocomplete service will return candidate matches based on this string and order results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/autocomplete)

---

<a name=query-autocomplete></a>
# Query Autocomplete
### queryAutocomplete($input, $params = [])
* `input` — The text string on which to search. The Places service will return candidate matches based on this string and order results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/query)

---

<a name=place-photo></a>
# Place Photo
### photo($photoReference, $params = [])
* `params` - The set of key-value parameters necessary to add a place to Google. You can refer to the fields on [Google's Official Webpage regarding Place Add](https://developers.google.com/places/web-service/photos)

---

<a name=custom-headers></a>
# Custom Headers
### withHeaders(array $headers)
Call This method before any other methods to set the headers. You can chain this method.

### new PlacesApi($key = null, $verifySSL = true, array $headers = [])
To have custom headers set for every call, you can pass 3rd parameter as the headers to class constructor.

**Note:** For Laravel Users, you can set this in config file with key `headers`

---

<a name=additional-methods></a>
# Additional Methods
### getStatus()
This will return the status of the response send by google api. Use it after making any request.

### getKey()
This will return the `API KEY` been used with the requests.

### setKey($key)
This will set the `API KEY`.

### verifySSL($verifySSL = true)
You can pass `false` to disable Verification of SSL Certification.

**Note:** For Laravel Users, you can set this in config file with key `verify_ssl` 

Or You can Pass the path to the certificate.

<a name=exceptions></a>
# Exceptions
Google Places API may throw various exceptions based on the given `$params` or response and is located in the `SKAgarwal\GoogleApi\Exceptions` namespace.

- A `GooglePlacesApiException` is thrown when no `API KEY` is provided or  `$params` is invalid. 
**Note:** This is the parent class for the preceding exceptions.
- A `InvalidRequestException` is thrown when the response `status` is `INVALID_REQUEST`
- A `OverQueryLimitException` is thrown when  the response `status` is `OVER_QUERY_LIMIT`
- A `RequestDeniedException` is thrown when  the response `status` is `REQUEST_DENIED`
- A `UnknownErrorException` is thrown when  the response `status` is `UNKNOWN_ERROR`
- A `NotImplementedException` is thrown when the response cannot be determined.

If any of these exception has been thrown, you can use the `getErrorMessage()` method to get the `error_message` field from the response if any is provided. 
**Note:** `error_message` field is not guaranteed to be always present, and its content is subject to change.

# Contribution
Feel free to report issues or make Pull Requests.
If you find this document can be improved in any way, please feel free to open an issue for it.

# License

The Google Places Api is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
