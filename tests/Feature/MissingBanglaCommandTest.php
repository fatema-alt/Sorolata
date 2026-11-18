<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissingBanglaCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_bn_command_executes_successfully(): void
    {
        $this->artisan('i18n:missing-bn')
            ->expectsOutputToContain('Sorolata Database Bilingual Content Audit')
            ->assertSuccessful();
    }

    public function test_missing_bn_command_supports_filtering_by_model(): void
    {
        $this->artisan('i18n:missing-bn', ['--model' => 'Product'])
            ->expectsOutputToContain('Product')
            ->assertSuccessful();
    }
}
