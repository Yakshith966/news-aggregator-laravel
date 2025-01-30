<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preferencable_id' => 'required|integer',
            'preferencable_type' => 'required|string|in:category,author,source',
        ];
    }
}
