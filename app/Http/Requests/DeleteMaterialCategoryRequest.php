<?php

namespace App\Http\Requests;

use App\Models\MaterialCategory;
use Store;

use App\Http\Requests\Request;
use App\Validators\Validator;

class DeleteMaterialCategoryRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = Validator::make();

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

        $materialCategoryID = $this->route('material_category');

        try
        {
            $materialCategory = MaterialCategory::findOrFail($materialCategoryID);
            Store::set('material_category', $materialCategory);

            return true;
        }
        catch (ModelNotFoundException $e)
        {
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
