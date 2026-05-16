<?php

namespace Tests\Unit;

use App\Rules\PhilippineMobile;
use PHPUnit\Framework\TestCase;

class PhilippineMobileRuleTest extends TestCase
{
    public function test_valid_numbers_pass(): void
    {
        $rule = new PhilippineMobile;
        $fails = [];
        $fail = function () use (&$fails) { $fails[] = func_get_args(); };

        foreach (['09171234567', '+639171234567'] as $valid) {
            $rule->validate('phone', $valid, $fail);
        }
        $this->assertSame([], $fails);
    }

    public function test_invalid_numbers_fail(): void
    {
        $rule = new PhilippineMobile;
        $fails = 0;
        $fail = function () use (&$fails) { $fails++; };

        foreach (['1234', '+1234567890', '0917-123-4567'] as $bad) {
            $rule->validate('phone', $bad, $fail);
        }
        $this->assertSame(3, $fails);
    }
}
