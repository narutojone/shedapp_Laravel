<?php

namespace App\Validators;

use App\Models\Option;
use App\Models\BuildingModel;

use App\Validators\EnchantValidatorTrait;
use Fadion\ValidatorAssistant\ValidatorAssistant;

class OptionPackageValidator extends ValidatorAssistant {

    use EnchantValidatorTrait;

    protected $messages = array(
        "is_valid_package_options" => "Option is not valid",
        "is_valid_package_options_price" => "Option price is not valid",
        "is_valid_allowable_models" => "Allowable model is not valid",
    );

    /**
     * Validate options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidPackageOptionsPrice($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        foreach ( $value as $price )
        {
            if ( !is_numeric($price) )
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }

    /**
     * Validate options
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidPackageOptions($attribute, $value, $parameters)
    {
        $validator = $this->instance();
        $is_valid = true;

        if ( count($value) === 0 )
        {
            $is_valid = false;
            return $is_valid;
        }

        $options = Option::active()->get()->pluck('name', 'id')->toArray();
        foreach ( $value as $option_id )
        {
            if ( !isset($options[$option_id]))
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

        $models = BuildingModel::active()->get()->pluck('name', 'id')->toArray();
        foreach ( $value as $model_id )
        {
            if ( !isset($models[$model_id]))
            {
                $is_valid = false;
            }
        }

        return $is_valid;
    }
}