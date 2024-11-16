
[![Latest Stable Version](https://poser.pugx.org/skagarwal/google-places-api/v/stable?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![Latest Unstable Version](https://poser.pugx.org/skagarwal/google-places-api/v/unstable?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![Total Downloads](https://poser.pugx.org/skagarwal/google-places-api/downloads?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![License](https://poser.pugx.org/skagarwal/google-places-api/license?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)


# Google Places API.

This is a PHP wrapper for **Google Places API Web Service**. And is [Laravel Framework](https://laravel.com) friendly.

### Version Supports
| Version  | PHP    | Laravel      | Google Places API                                                                                                                                                          |
|----------|--------|--------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| ^3.0     | ^8.1   | ^10\|^11     | [Place API](https://developers.google.com/places/web-service/search) \| [Place API (New)](https://developers.google.com/maps/documentation/places/web-service/op-overview) |
| [^2.2]((https://github.com/SachinAgarwal1337/google-places-api/tree/2.2.0)) | ^8.0.2 | ^9\|^10\|^11 | [Place API](https://developers.google.com/places/web-service/search)                                                                                                       |


## Note

V3 is a complete re-write from scratch using [Saloon](https://docs.saloon.dev/) and has major breaking changes. Please refer to the [Upgrade Guide]() for more information.<br>
But is also backward compatibility with [^2.2]((https://github.com/SachinAgarwal1337/google-places-api/tree/2.2.0))<br>
Backward compatibility will be removed in the next major release.


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

```php
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;


public function foo() {
  $response = GooglePlaces::make(key: 'API KEY', verifySSL: false, throwOnError: false)->autocomplete('some input');
  
  $data = $response->array();
}

```

**Note:** You can also set the **API KEY** after initiating the class using `GooglePlaces::make()->setKey('KEY')` method.


---

<a name=laravel-usage></a>
# Use with Laravel


## Step 1
publish the config file with following artisan command
```
php artisan vendor:publish --provider="SKAgarwal\GoogleApi\ServiceProvider"
```

This will create **google.php** file in the config directory.

Set the *API KEY* in this config file.

## Set 2
Start making requests

```php
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;


public function foo() {
  $response = GooglePlaces::make()->autocomplete('some input');
  
  $data = $response->collect();
}

```

---
# Response
The response returned is a [Saloon's Response](https://docs.saloon.dev/the-basics/responses#useful-methods). You can refer to the documentation for more information.

---

# Available Requests

## Places API

<a name=place-search></a>
## Place Search
### nearbySearch(string \$location, ?string \$radius = null, array $params = [])
* `location` — The latitude/longitude around which to retrieve place information. This must be specified as latitude, longitude.
* `radius` — Defines the distance (in meters) within which to return place results. The maximum allowed radius is 50 000 meters. Note that `radius` must not be included if `rankby=distance` (described under **Optional parameters** below) is specified.
* If `rankby=distance` (described under **Optional parameters** below) is specified, then one or more of `keyword`, `name`, or `types` is required.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search)

### textSearch(string \$query, array \$params = [])
* `query` — The text string on which to search, for example: "restaurant". The Google Places service will return candidate matches based on this string and order the results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search)

### findPlace(string \$input, string \$inputType, array \$params = []) 
* `input` — The text input specifying which place to search for (for example, a name, address, or phone number).
* `inputType` — The type of input. This can be one of either textquery or phonenumber. Phone numbers must be in international format (prefixed by a plus sign ("+"), followed by the country code, then the phone number itself).
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/search#FindPlaceRequests)

---

<a name=place-details></a>
# Place Details
### placeDetails(string \$placeId, array \$params = [])
* `placeId` — A textual identifier that uniquely identifies a place, returned from a Place Search.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/details)

---

<a name=place-autocomplete></a>
# Place Autocomplete
### placeAutocomplete(string \$input, array \$params = [])
* `input` — The text string on which to search. The Place Autocomplete service will return candidate matches based on this string and order results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/autocomplete)

---

<a name=query-autocomplete></a>
# Query Autocomplete
### queryAutocomplete(string \$input, array \$params = [])
* `input` — The text string on which to search. The Places service will return candidate matches based on this string and order results based on their perceived relevance.
* `params` - **Optional Parameters** You can refer all the available optional parameters on the [Google's Official Webpage](https://developers.google.com/places/web-service/query)

---

<a name=place-photo></a>
# Place Photo
### photo(string $photoReference, array $params = []): ?string
* `params` - The set of key-value parameters necessary to add a place to Google. You can refer to the fields on [Google's Official Webpage regarding Place Add](https://developers.google.com/places/web-service/photos)
* Returns the URL of the photo.

---

<a name=custom-headers></a>
# Custom Headers
### GooglePlaces::make()->headers()->add(string \$key, mixed \$value)
Call This method before making any request to set custom headers.

### Default Headers (Laravel only)
To have custom headers set for every call, you can set `headers` in the config file

---

<a name=additional-methods></a>
# Additional Methods
### setKey(string \$key)
This will set the `API KEY`.

### verifySSL(bool \$verifySSL = true)
You can pass `false` to disable Verification of SSL Certification.

**Note:** For Laravel Users, you can set this in config file with key `verify_ssl` 

### throwOnErrors(bool \$throwOnError)
By default, when request to Google places API fails, no exception is thrown. You can check the request status by calling `$response->status()`  
You can change this behaviour by setting `throwOnError` to `true`. This will throw an exception if the request fails.

# Contribution
Feel free to report issues or make Pull Requests to develop branch.
If you find this document can be improved in any way, please feel free to open an issue/PR for it.

# License

The Google Places Api is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
