<?php

namespace App\Services\Building;

use App\Models\Building;
use App\Models\BuildingHistory;
use App\Models\BuildingStatus;
use App\Models\BuildingOptionColor;

use App\Services\Files\FileService;
use App\Services\Building\BuildingStatusService;

use App\Events\BuildingWasAdded;
use App\Events\BuildingWasUpdated;

use App\Events\BuildingHistoryWasAdded;

use DB;
use Event;
use Auth;
use Helper;

class BuildingService
{
    public function saveColors($buildingOption, $color) {
        // Add color
        $buildingOptionColor = BuildingOptionColor::firstOrNew(['building_option_id' => $buildingOption->id]);
        $buildingOptionColor->building_option_id = $buildingOption->id;
        $buildingOptionColor->color_id = $color['id'];

        if ($color['name']) {
            $buildingOptionColor->custom = $color['name'];
        }

        $buildingOptionColor->save();
        return $this;
    }

    public function saveFiles($building, $buildingData = []) {
        // Add files
        if (isset($buildingData['files'])) {
            $fileService = new FileService();
            $fileService->store($buildingData['files'], [
                'key' => $building->serial_number,
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'type' => 'building',
                'id' => $building->id,
                'category_id' => $buildingData['category_id'] ?? null
            ]);
        }
        return $this;
    }

    public function saveOptions($building, $buildingData) {
        // Add options
        if (array_key_exists('options', $buildingData)) {
            $building->building_options()->delete();
            foreach((array) $buildingData['options'] as $key => $bOption)
            {
                $optionUnitPrice = $bOption['unit_price'];
                $optionQuantity = $bOption['quantity'];

                $buildingOption = $building->building_options()->create([
                    'option_id' => $bOption['option_id'],
                    'total_price' => $optionUnitPrice * $optionQuantity,
                    'unit_price' => $optionUnitPrice,
                    'quantity' => $optionQuantity
                ]);

                if (!empty($bOption['color'])) {
                    $this->saveColors($buildingOption, $bOption['color']);
                }
            }
        }
        return $this;
    }

    public function save(Building $building, $buildingData = [], $options = []) {
        if ($building->id)
            return $this->update($building, $buildingData, $options);
        return $this->create($buildingData, $options);
    }

    public function create($buildingData = [], $options = []) {
        Helper::load('Building');

        if (array_key_exists('update_serial_number', $options) && $options['update_serial_number'] === true) {
            $serialNumber = building_get_serial_number($buildingData);

            $buildingData['sort_id'] = substr($serialNumber, 12, 3) . substr($serialNumber, -2) . substr($serialNumber, -6, 4);
            $buildingData['manufacture_year'] = '20'.substr($serialNumber, -2);
            $buildingData['serial_number'] = $serialNumber;
        }

        if (array_key_exists('shell_price', $buildingData))
            $buildingData['total_price'] = $buildingData['shell_price'];

        if (!array_key_exists('plant_id', $buildingData))
            $buildingData['plant_id'] = 1; // default plant

        $building = new Building($buildingData);
        $building->save();

        $this->saveOptions($building, $buildingData);
        $this->saveFiles($building, $buildingData);

        Event::fire(new BuildingWasAdded(
            $building,
            Auth::user(),
            isset($options['defaultStatus']) ? $options['defaultStatus'] : false
        ));
        Event::fire(new BuildingWasUpdated($building));

        return $building;
    }

    public function update($building, $buildingData = [], $options = []) {
        Helper::load('Building');

        if (array_key_exists('update_serial_number', $options) && $options['update_serial_number'] === true) {
            $serialData['building_model_id'] = $building->building_model_id;
            $serialData['width'] = $building->width;
            $serialData['length'] = $building->length;
            $serialData['height'] = $building->height;
            $serialData['plant_id'] = $building->plant_id;
            $serialData = array_merge($serialData, $buildingData);
            $serialNumber = building_get_serial_number($serialData, $building->serial_number);
            $buildingData['serial_number'] = $serialNumber;
            $buildingData['sort_id'] = substr($serialNumber, 12, 3) . substr($serialNumber, -2) . substr($serialNumber, -6, 4);
            $buildingData['manufacture_year'] = '20'.substr($serialNumber, -2);
        }

        // add next status (by priority) to building
        if (array_key_exists('next_status', $options) && $options['next_status'] === true) {
            $buildingData['status_id'] = $this->addStatus($building, Auth::user());
        }

        $building->update($buildingData);

        $this->saveOptions($building, $buildingData);
        $this->saveFiles($building, $buildingData);

        Event::fire(new BuildingWasUpdated($building));

        return $building;
    }

    /**
     * Add new NEXT status by priority to building and re-index status_id
     * @param $building
     * @param $user
     * @param $contractor
     * @return mixed
     */
    public function addStatus(&$building, $user = null, $contractor = null) {
        $buildingStatus = BuildingStatus::where('default_for_sale', 1)->firstOrFail();

        $buildingHistory = new BuildingHistory([
            'building_id' => $building->id,
            'status_id' => $buildingStatus->id
        ]);

        if ($user) $buildingHistory->user_id = $user->id;
        if ($contractor) $buildingHistory->contractor_id = $contractor->id;

        $buildingHistory = $building->building_history()->save($buildingHistory);
        return $buildingHistory->id;
    }
}
