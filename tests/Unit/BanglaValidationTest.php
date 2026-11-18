<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class BanglaValidationTest extends TestCase
{
    public function test_bangla_validation_messages_and_attributes(): void
    {
        app()->setLocale('bn');

        $validator = Validator::make(
            [
                'name' => '',
                'email' => 'invalid-email',
                'password' => '123',
            ],
            [
                'name' => 'required',
                'email' => 'email',
                'password' => 'min:6',
            ]
        );

        $this->assertTrue($validator->fails());
        $errors = $validator->errors();

        // Check required attribute replacement
        $this->assertSame('নাম পূরণ করা আবশ্যক।', $errors->first('name'));
        // Check email rule
        $this->assertSame('ইমেইল অবশ্যই একটি সঠিক ইমেইল ঠিকানা হতে হবে।', $errors->first('email'));
        // Check min rule
        $this->assertSame('পাসওয়ার্ড কমপক্ষে 6 অক্ষরের হতে হবে।', $errors->first('password'));
    }
}
