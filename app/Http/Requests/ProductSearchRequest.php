<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
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
            'query' => 'string|nullable',
            'category' => 'integer|nullable|exists:categories,id',
            'per_page' => 'integer|min:1|max:20',
            'page' => 'integer|min:1'
        ];
    }
}
