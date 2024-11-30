[![Latest Stable Version](https://poser.pugx.org/skagarwal/google-places-api/v/stable?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![Total Downloads](https://poser.pugx.org/skagarwal/google-places-api/downloads?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)
[![License](https://poser.pugx.org/skagarwal/google-places-api/license?format=flat-square)](https://packagist.org/packages/skagarwal/google-places-api)

# Google Places API for PHP

A PHP wrapper for **Google Places API Web Service**, compatible with [Laravel](https://laravel.com).

## Version Compatibility

| Package Version |  PHP   |     Laravel      | Google Places API                                                                                                                                                           |
|:---------------:|:------:|:----------------:|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|      ^3.0       |  ^8.1  |    ^10 \| ^11    | [Places API](https://developers.google.com/places/web-service/search) and [Places API (New)](https://developers.google.com/maps/documentation/places/web-service/op-overview) |
|      ^2.2       | ^8.0.2 | ^9 \| ^10 \| ^11 | [Places API](https://developers.google.com/places/web-service/search)                                                                                                       |

> [!CAUTION]
> Version 3 is a complete rewrite using [Saloon](https://docs.saloon.dev/) with breaking changes.    
> v2.2 API is deprecated and will be removed in next major version. Which means it is backward compatible.   
> And it is recommended to shift to v3 API before next major release.   
> You can refer to v2.2 documentation [here](README-Legacy.md).


## Installation
Install it with composer
```
composer require skagarwal/google-places-api
```


## General Usage

**Laravel user can see the [Laravel Usage](#laravel-usage) section**

```php
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;


public function foo() {
  $response = GooglePlaces::make(key: 'API KEY', verifySSL: false, throwOnError: false)->autocomplete('some input');
  
  $data = $response->array();
}

```

>You can also set the **API KEY** after initiating the class using `GooglePlaces::make()->setKey('KEY')` method.


---

<a name="laravel-usage"></a>
## Laravel


### Step 1
publish the config file with following artisan command
```
php artisan vendor:publish --provider="SKAgarwal\GoogleApi\ServiceProvider"
```

This will create **[google.php](config/google.php)** file in the config directory.

Set the **_API_ KEY** in this config file.

### Step 2
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

The response returned is a [Saloon's Response](https://docs.saloon.dev/the-basics/responses#useful-methods) thus you can use all the methods provided by Saloon.
    
```php
$response->array(); // returns the response as array
$response->collect(); // returns the response as collection
$response->json(); // returns the response as json
$response->status(); // returns the status of the response
$response->headers(); // returns the headers of the response
$response->body(); // returns the body of the response
$response->throw(); // throws an exception if the response is not successful
```

You can refer to Saloon's documentation for more methods.

> [!IMPORTANT]  
> By default, no exception is thrown for API errors. You can check the request status with `$response->status()`.  
> You can also use `GooglePlaces::make()->findPlace()->throw()` to throw an exception if the request fails.

---

# API Reference

This library supports both the **[Places API (original)](#places-original)** and the **[Places API (New)](#places-new)**.

<a name=places-original></a>
## Places API
This section covers methods available for original Places API.
```php
use SKAgarwal\GoogleApi\Places\GooglePlaces; // Original Places API class

public function foo() {
  $response = GooglePlaces::make()->nearbySearch('40.748817,-73.985428');
  
  $data = $response->array();
}
```

### Place Search

#### `findPlace(string $input, string $inputType, array $params = [])`
- **$input**: Text to search (e.g., name, address).
- **$inputType**: `textquery` or `phonenumber`.
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/search-find-place).

#### `nearbySearch(string $location, ?string $radius = null, array $params = [])`
- **$location**: Latitude,Longitude coordinates. Order - (lat,lng) (e.g., `40.748817,-73.985428`).
- **$radius**: Distance in meters (max 50,000). Required unless using `rankby=distance`.
- **$params**: Optional parameters (e.g., `keyword`, `type`). [More info](https://developers.google.com/maps/documentation/places/web-service/search-nearby).

#### `textSearch(string $query, array $params = [])`
- **$query**: Search string (e.g., `"restaurant"`).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/search-text).

---

### Place Details

#### `placeDetails(string $placeId, array $params = [])` 
- **$placeId**: Unique identifier for a place.
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/details).

---

### Place Autocomplete
#### `placeAutocomplete(string $input, array $params = [])`
- **$input**: Text to search (e.g., name, address).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/autocomplete).

---

### Query Autocomplete
#### `queryAutocomplete(string $input, array $params = [])`
- **$input**: Text to search (e.g., name, address).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/query).

---

### Place Photo
#### `photo(string $photoReference, array $params = [])`
- **$photoReference**: Reference to a photo. [More info](https://developers.google.com/maps/documentation/places/web-service/photos#photo_references)  
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/photos).

---
<a name=places-new></a>
## Places API (New)

This section covers methods available for Places API (New).   

```php
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces; // New Places API class

public function foo() {
  $response = GooglePlaces::make()->nearbySearch(40.748817, -73.985428, 500.0);
  
  $data = $response->array();
}
```
---

### Autocomplete
#### `autocomplete(string $input, bool $includeQueryPredictions = false, ?array $fields = null, array $params = [])`
- **$input**: Text to search (e.g., name, address).
- **$fields**: Fields to return. [More info](https://developers.google.com/maps/documentation/places/web-service/choose-fields).
- **$includeQueryPredictions**: If `true`, the response includes both place and query predictions. The default value is **false**, meaning the response only includes place predictions.
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/place-autocomplete).

### Nearby Search
#### `nearbySearch(float $latitude, float $longitude, float $radius = 0.0, array $fields = ['*'], array $params = [])`
- **$latitude**: Latitude of the location.
- **$longitude**: Longitude of the location.
- **$radius**: The radius must be between **0.0** and **50000.0**, inclusive. The default radius is 0.0. You must set it in your request to a value greater than 0.0.
- **$fields**: Fields to return. Default is all fields. [More info](https://developers.google.com/maps/documentation/places/web-service/choose-fields).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/nearby-search).

### Place Details
#### `placeDetails(string $placeId, array $fields = ['*'], array $params = [])`
- **$placeId**: Unique identifier for a place.
- **$fields**: Fields to return. Default is all fields. [More info](https://developers.google.com/maps/documentation/places/web-service/choose-fields).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/place-details).

### Text Search
#### `textSearch(string $textQuery, array $fields = ['*'], array $params = [])`
- **$textQuery**: Search string (e.g., `"restaurant"`).
- **$fields**: Fields to return. Default is all fields. [More info](https://developers.google.com/maps/documentation/places/web-service/choose-fields).
- **$params**: Optional parameters. [More info](https://developers.google.com/maps/documentation/places/web-service/text-search).

### Place Photo
#### `placePhoto(string $name, int $maxHeightPx = null, int $maxWidthPx = null)`
- **$name**: A string identifier that uniquely identifies a photo. [More Info](https://developers.google.com/maps/documentation/places/web-service/place-photos#photo-name)
- **$maxHeightPx**: The maximum desired height of the image in pixels. (Should be between 1 and 4800)
- **$maxWidthPx**: The maximum desired width of the image in pixels. (Should be between 1 and 4800)
> [skipHttpRedirect](https://developers.google.com/maps/documentation/places/web-service/place-photos#skiphttpredirect) is set to false internally to get JSON response. This cannot be changed

---

<a name=custom-headers></a>
# Custom Headers
#### Set Custom Headers
```php
GooglePlaces::make()->headers()->add('Header-Key', 'Header-Value');
```

---

<a name=additional-methods></a>
# Additional Methods

- **`setKey(string $key)`**: Set the API key.
- **`getKey(string $key)`**: Get the API key being used.
- **`verifySSL(bool $verifySSL = true)`**: Enable/disable SSL verification.
- **`throwOnErrors(bool $throwOnError)`**:
    - By default, no exception is thrown for API errors. You can check the request status with `$response->status()`.
    - When `throwOnError` is set to `true`, the library will throw exceptions on API failures.

---

# Contribution
- Report issues or contribute to the `develop` branch.
- Open issues/PRs to improve this documentation.

---

# License
This package is licensed under the [MIT License](http://opensource.org/licenses/MIT).

