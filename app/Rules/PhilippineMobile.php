<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhilippineMobile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Accept formats: 09XXXXXXXXX  or  +639XXXXXXXXX
        if (! is_string($value) || ! preg_match('/^(09\d{9}|\+639\d{9})$/', $value)) {
            $fail('The :attribute must be a valid Philippine mobile number (09XXXXXXXXX or +639XXXXXXXXX).');
        }
    }
}
