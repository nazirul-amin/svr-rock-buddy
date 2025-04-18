<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'theme_ids' => 'array',
            'theme_ids.*' => 'exists:themes,id',
        ];
    }
}
