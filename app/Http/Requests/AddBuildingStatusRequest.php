<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddBuildingStatusRequest extends Request
{
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
        $this->rules['type'] = 'required|in:build,sale';
        $this->rules['name'] = 'required|string|max:255';
        $this->rules['priority'] = 'required|numeric';
        $this->rules['is_active'] = 'in:yes,no';
        return $this->rules;
    }
}
