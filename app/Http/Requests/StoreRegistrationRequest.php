<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class StoreRegistrationRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'area' => ['required', 'numeric', 'integer', 'min:1'],
            'number_floors' => ['required', 'numeric', 'integer', 'min:1'],
            'number_rooms' => ['required', 'numeric', 'integer', 'min:1'],
            'apartment_type_id' => ['required', 'exists:apartment_types,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'number_floors.required' => 'The number floors field is required.',
            'number_floors.numeric' => 'The number floors field must be a numeric.',
            'number_floors.integer' => 'The number floors field must be a integer.',
            'number_floors.min' => 'The number floors field must be at least :min.',
            'number_rooms.required' => 'The number rooms field is required.',
            'number_rooms.numeric' => 'The number rooms field must be a numeric.',
            'number_rooms.integer' => 'The number rooms field must be a integer.',
            'number_rooms.min' => 'The number rooms field must be at least :min.',
            'apartment_type_id.required' => 'The apartment type field is required.',
            'apartment_type_id.exists' => 'The apartment type field is invalid.',
        ];
    }
}
