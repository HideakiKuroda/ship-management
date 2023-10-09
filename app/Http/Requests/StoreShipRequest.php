<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>  ['required', 'max:50'],
            'ex_name'=> ['max:50'],
            'former_name'=> ['max:50'],
            'yard'=> ['max:50'],
            'ship_no'=> ['max:50'],
            'gross_tonn'=> ['nullable','integer'],
            'dock_term'=> ['nullable','integer'],
            
        ];
    }
}
