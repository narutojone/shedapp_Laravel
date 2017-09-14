<?php

namespace App\Services\Locations;

use App\Models\Location;

class LocationService
{
    /**
     * LocationService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $locationParams
     * @return Location
     */
    public function create(array $locationParams): Location {
        $location = new Location($locationParams);
        $location = (new GeoLocationService)->fetchLatLng($location);
        $location->save();
        return $location;
    }

    /**
     * @param Location $location
     * @param array    $locationParams
     * @return Location
     */
    public function update(Location $location, $locationParams): Location {
        $location = $location->fill($locationParams);
        $location = (new GeoLocationService)->fetchLatLng($location);
        $location->save();
        return $location;
    }
}