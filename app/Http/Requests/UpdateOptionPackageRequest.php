<?php

namespace App\Http\Requests;

use App\Models\OptionPackage;
use App\Http\Requests\Request;
use App\Validators\OptionPackageValidator;

class UpdateOptionPackageRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = OptionPackageValidator::make();

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too

        $optionPackageId = $this->route('option_package');

        try {
            $optionPackage = OptionPackage::findOrFail($optionPackageId);
            $this->validator->store('requestedOptionPackage', $optionPackage);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('name', 'required|string|max:255');
        $this->validator->addRule('description', 'string|max:255');
        $this->validator->addRule('options', 'array|is_valid_package_options|required_with:options_price');
        $this->validator->addRule('options_price', 'array|is_valid_package_options_price|required_with:options');
        $this->validator->addRule('allowable_models', 'array|is_valid_allowable_models');
        return $this->rules;
    }
}
