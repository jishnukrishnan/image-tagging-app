<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageTagRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'coordinates' => 'required|array|size:4',
            'coordinates.*' => 'required|array|size:2',
            'coordinates.*.*' => 'integer',
            'label' => 'required',
        ];
    }
}
