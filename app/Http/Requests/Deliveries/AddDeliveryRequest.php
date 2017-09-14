<?php

namespace App\Http\Requests\Deliveries;

use App\Models\Delivery;
use App\Http\Requests\Request;
use App\Validators\Validator;

class AddDeliveryRequest extends Request
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
        $this->validator = Validator::make($request)->addRules(Delivery::$rules);
        
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
        // $types = implode(',', array_keys(Delivery::$statuses));
        // $this->validator->append('status_id', "required|string|in:{$types}");
        $this->validator->append('status_id', 'nullable');
        $this->validator->append('sale_id', 'exists:sales,id,deleted_at,NULL');
        $this->validator->append('building_id', 'required|exists:buildings,id,deleted_at,NULL');
        $this->validator->append('location_start_id', 'exists:locations,id,deleted_at,NULL');
        $this->validator->append('location_end_id', 'exists:locations,id,deleted_at,NULL');
        $this->validator->addRule('assoc_end_location', 'in:1,0|nullable');
        return $this->rules;
    }
}
