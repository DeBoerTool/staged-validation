<?php

namespace Dbt\StagedValidation\Tests\Suites\Feature\Validation;

use Dbt\StagedValidation\Tests\Infra\StagedValidatorStub;
use Dbt\StagedValidation\Tests\Suites\Feature\TestCase;
use Illuminate\Support\Facades\Route;

class ValidationTest extends TestCase
{
    protected function setUp (): void
    {
        parent::setUp();

        Route::post('/scalar', function (StagedValidatorStub $stub) {
            return [
                'ok' => true
            ];
        });
    }

    /** @test */
    public function failing_scalar_validation (): void
    {
        $response = $this->call('POST', '/scalar', [
            'test_int' => 'some string'
        ]);

        $response->assertSessionHasErrors([
            'test_string' => 'The test string field is required.',
            'test_int' => 'The test int must be an integer.',
        ]);

        $response = $this->call('POST', '/scalar', [
            'test_string' => 'short',
            'test_int' => '1',
        ]);

        $response->assertSessionHasErrors([
            'test_string' => 'The test string must be at least 10 characters.',
            'test_int' => 'The test int must be at least 10.',
        ]);
    }
}
