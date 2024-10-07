<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $key = array_key_first($this->all());
        return [
            "$key.age" => 'required|integer|min:1|max:150',
            "$key.course" => 'required|integer|min:1|max:5',
            "$key.faculty" => 'required|integer|exists:faculties,id',
            "$key.interest" => 'array', // Валідуємо як масив
            "$key.interest.*" => 'integer|exists:interests,id', // Кожен елемент масиву має бути інтересом
            "$key.name" => 'required|string|max:255',
            "$key.about" => 'required|string|max:255',
            "$key.phone" => 'required|string|max:30',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }
}
