<?php

namespace App\Http\Requests\Materials;

use App\Models\Material;
use Store;

use App\Http\Requests\Request;
use App\Validators\MaterialValidator;

class UpdateMaterialRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $request = $this->all();
        array_walk_recursive($request, function(&$item, $key) {
            if ($item === 'null') $item = null;
        });
        Request::merge($request);
        $this->validator = MaterialValidator::make($request)->addRules(Material::$rules);

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

        $materialID = $this->route('material');

        try
        {
            $material = Material::findOrFail($materialID);
            Store::set('material', $material);

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
        $this->validator->append('name', 'required|string|max:50');
        $this->validator->append('description', 'required|string|min:3');
        $this->validator->append('option_id', 'numeric|exists:options,id,deleted_at,NULL');
        $this->validator->append('material_categories_id', 'array|is_valid_material_categories');
        $this->validator->append('allowable_models_id', 'array|is_valid_allowable_models');
        $this->validator->append('allowable_colors_id', 'array|is_valid_allowable_colors');
        return $this->rules;
    }
}
