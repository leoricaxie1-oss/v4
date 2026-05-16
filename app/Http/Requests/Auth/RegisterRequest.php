<?php

namespace App\Http\Requests\Auth;

use App\Rules\PhilippineMobile;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'   => ['required', 'string', 'max:60'],
            'middle_name'  => ['nullable', 'string', 'max:60'],
            'last_name'    => ['required', 'string', 'max:60'],
            'suffix'       => ['nullable', 'string', 'max:10'],
            'email'        => ['required', 'string', 'email:rfc,dns', 'max:120', 'unique:users,email'],
            'phone'        => ['required', new PhilippineMobile, 'unique:users,phone'],

            // Address (dependent dropdowns)
            'province_id'  => ['required', 'integer', 'exists:provinces,id'],
            'city_id'      => ['required', 'integer', 'exists:cities,id'],
            'barangay_id'  => ['required', 'integer', 'exists:barangays,id'],
            'purok_id'     => ['required', 'integer', 'exists:puroks,id'],

            'password'     => ['required', 'confirmed', new StrongPassword],

            'terms'        => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name'  => 'last name',
            'phone'      => 'mobile number',
        ];
    }
}
