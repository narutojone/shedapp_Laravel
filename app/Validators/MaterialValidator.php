<?php

namespace App\Validators;

use App\Models\Color;
use App\Models\BuildingModel;
use App\Models\MaterialCategory;
use App\Validators\EnchantValidatorTrait;
use App\Validators\Validator;

class MaterialValidator extends Validator {

    use EnchantValidatorTrait;

    protected $attributes = array(
        'material_categories' => 'Allowable categories',
        'allowable_models_id' => 'Allowable model',
        'allowable_colors_id' => 'Allowable color'
    );

    protected $messages = array(
        "is_valid_material_categories" => "Material Category is not valid",
        "is_valid_allowable_models" => "Allowable models is not valid",
        "is_valid_allowable_colors" => "Allowable colors is not valid",
    );

    /**
     * Validate material categories
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidMaterialCategories($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $materialCategories = MaterialCategory::get()->pluck('name', 'id')->toArray();

        foreach ( $value as $model_id )
        {
            if ( $model_id == 'all') continue;
            if ( !isset($materialCategories[$model_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }

    /**
     * Validate allowable models
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidAllowableModels($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $buildingModels = BuildingModel::active()
            ->with('style')->whereHas('style', function ($query) {
                $query->where('is_active', 'yes');
            })->get()->pluck('name', 'id')->toArray();

        foreach ( $value as $model_id )
        {
            if ( $model_id == 'all') continue;
            if ( !isset($buildingModels[$model_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }

    /**
     * Validate allowable colors
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidAllowableColors($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $colors = Color::active()->get()->pluck('name', 'id')->toArray();

        foreach ( $value as $color_id )
        {
            if ( $color_id == 'all') continue;
            if ( !isset($colors[$color_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }
}
