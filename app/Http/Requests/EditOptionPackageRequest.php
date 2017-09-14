<?php

namespace App\Http\Requests;

use App\Models\OptionPackage;
use App\Http\Requests\Request;
use App\Validators\OptionPackageValidator;

class EditOptionPackageRequest extends Request
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
        return $this->rules;
    }
}
