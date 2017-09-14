<?php

namespace App\Http\Requests;

use Store;

use App\Models\Option;
use App\Http\Requests\Request;
use App\Validators\OptionValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateOptionRequest extends Request
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
        $this->validator = OptionValidator::make($request)->addRules(Option::$rules);

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
        $id = $this->route('option');

        try {
            $option = Option::where('id', $id)->firstOrFail();
            Store::set('option', $option);

            return true;
        } catch (Exception $e) {
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
        $this->validator->append('category_id', 'exists:option_categories,id');

        $this->validator->addRule('allowable_models_id', 'array|nullable|is_valid_allowable_models');
        // $this->validator->addRule('options', 'exists:options,id');

        // multiple files
        $nbr = count($this->input('upload_files')) - 1;
        foreach(range(0, $nbr) as $index) {
            $this->validator->addRule('upload_files.' . $index, 'file');
        }

        return $this->rules;
    }
}
