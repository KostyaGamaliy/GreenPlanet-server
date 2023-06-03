<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|string|min:3|max:25',
            'email' => 'required|string|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Поле повинно бути заповненим',
            'full_name.min' => 'Повне ім\'я повинно мати більше 3 букв',
            'full_name.max' => 'Повне ім\'я повинно мати менше 25 букв',
            'email.required' => 'Поле повинно бути заповненим',
            'email.unique' => 'Такий email вже існує',
            'password.required' => 'Поле повинно бути заповненим',
            'password.min' => 'Пароль повинен бути більше 8 символів',
            'image.image' => 'Це поле повинно містити картинку',
            'image.mimes' => 'Це поле має бути названо з розширенням: jpeg,png,jpg,gif,svg',
            'image.max' => 'Розмір зображення повинен бути менше, ніж 2048',
        ];
    }
}
