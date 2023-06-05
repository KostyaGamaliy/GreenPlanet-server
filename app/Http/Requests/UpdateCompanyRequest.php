<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:100',
            'description' => 'string|min:5|max:1000',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле повинно бути заповненим',
            'name.min' => 'Назва повинна мати більше 2 букв',
            'name.max' => 'Назва повинна мати менше 100 букв',
            'description.min' => 'Опис повинен бути хоча б 3 літери',
            'description.max' => 'Занадто багато тексту',
            'location.required' => 'Поле повинно бути заповненим',
            'location.max' => 'Занадто велика назва',
            'image.image' => 'Це поле повинно містити картинку',
            'image.mimes' => 'Це поле має бути названо з розширенням: jpeg,png,jpg,gif,svg',
            'image.max' => 'Розмір зображення повинен бути менше, ніж 2048',
        ];
    }
}
