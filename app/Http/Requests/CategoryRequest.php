<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'is_publish' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kategori harus diisi.',
            'name.string' => 'Nama kategori harus berupa string.',
            'name.max' => 'Panjang nama kategori maksimal 255 karakter.',
            'is_publish.boolean' => 'Nilai is_publish harus berupa boolean.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama Kategori',
            'is_publish' => 'Status Publish',
        ];
    }
}
