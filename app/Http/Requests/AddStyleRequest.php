<?php

namespace App\Http\Requests;

use App\Models\Style;
use App\Http\Requests\Request;
use App\Validators\StyleValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddStyleRequest extends Request
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
        $this->validator = StyleValidator::make($request)->addRules(Style::$rules);

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
        $this->validator->append('name', 'required');
        $this->validator->append('description', 'nullable');
        $this->validator->append('icon_path', 'nullable');

        return $this->rules;
    }
}
