<?php

namespace App\Http\Requests\Esigns;

use Route;
use App\Http\Requests\Request;
use Illuminate\Http\Response;

class EsignOrderRequestBySignatureId extends Request
{
    public $redirectOnFail = false;

    /**
     * @return array
     */
    public function all()
    {
        return array_replace_recursive(parent::all(), $this->route()->parameters());
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
        return [
            'order_uuid' => ['required', 'uuid'],
            'signature_id' => ['required', 'string', 'regex:'.REGEX_MD5],
        ];
    }

    public function errorResponse($errors)
    {
        return new Response(view('esign.errors')->withErrors($errors, 422));
    }
}
