<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlantRequest extends FormRequest
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
            'name' => 'required|min:2|max:100',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'watering_time' => 'date_format:Y-m-d H:i:s',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'watering_time.date_format' => 'Невірно вказано формат дати',
            'name.required' => 'Поле повинно бути заповненим',
            'name.min' => 'Назва повинна мати більше 2 букв',
            'name.max' => 'Назва повинна мати менше 100 букв',
            'image.image' => 'Це поле повинно містити картинку',
            'image.mimes' => 'Це поле має бути названо з розширенням: jpeg,png,jpg,gif,svg',
            'image.max' => 'Розмір зображення повинен бути менше, ніж 2048',
            'company_id.required' => 'Поле повинно бути заповненим',
        ];
    }
}
