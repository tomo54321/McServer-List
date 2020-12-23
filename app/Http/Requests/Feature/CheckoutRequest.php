<?php

namespace App\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
        return [
            'server' => [ 
                'required', 
                'integer', 
                Rule::exists("servers", "id")->where("user_id", auth()->user()->id)
            ],
            'feature_length' => [
                'required',
                'integer',
                'min:' . config("serverlist.minsponsordays"),
                'max:' . config("serverlist.maxsponsordays"),
            ]
        ];
    }
}
