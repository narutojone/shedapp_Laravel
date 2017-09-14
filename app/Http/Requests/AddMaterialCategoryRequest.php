<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Validators\MaterialCategoryValidator;

class AddMaterialCategoryRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = MaterialCategoryValidator::make();

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('name', 'required|string|max:50');
        return $this->rules;
    }
}
