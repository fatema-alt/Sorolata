<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StorageDeliveryTest extends TestCase
{
    use RefreshDatabase;
    protected function tearDown(): void
    {
        $testFile = storage_path('app/public/test_artifact.txt');
        if (File::exists($testFile)) {
            File::delete($testFile);
        }

        parent::tearDown();
    }

    public function test_it_serves_files_from_storage_app_public(): void
    {
        $testFile = storage_path('app/public/test_artifact.txt');
        File::ensureDirectoryExists(dirname($testFile));
        File::put($testFile, 'SOROLATA_TEST_ASSET_CONTENT');

        $response = $this->get('/storage/test_artifact.txt');

        $response->assertStatus(200);
        $this->assertEquals('SOROLATA_TEST_ASSET_CONTENT', file_get_contents($response->baseResponse->getFile()->getPathname()));
    }

    public function test_it_returns_404_for_non_existent_file(): void
    {
        $response = $this->get('/storage/definitely_missing_file_xyz123.jpg');

        $response->assertStatus(404);
    }

    public function test_it_blocks_directory_traversal_attempts(): void
    {
        $response = $this->get('/storage/../../.env');

        $response->assertStatus(404);
    }
}
