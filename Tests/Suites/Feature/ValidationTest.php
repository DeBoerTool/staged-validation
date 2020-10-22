<?php

namespace Dbt\StagedValidation\Tests\Suites\Feature;

use Dbt\StagedValidation\Tests\Infra\Stubs\StageBundleStub;
use Dbt\StagedValidation\Tests\Infra\TestEntity;
use Illuminate\Support\Facades\Route;

/**
 * @internal
 */
class ValidationTest extends TestCase
{
    /** @var string */
    public const ENDPOINT = '/validate';

    protected function setUp (): void
    {
        parent::setUp();

        Route::post(self::ENDPOINT, function (StageBundleStub $bundle) {
            return [
                'ok' => true,
                'all' => $bundle->all(),
                'scalar' => $bundle->get('scalar'),
                'object' => $bundle->get('object'),
            ];
        });
    }

    /** @test */
    public function failing_scalar_validation (): void
    {
        $response = $this->call('POST', self::ENDPOINT, [
            'test_int' => 'some string',
        ]);

        $response->assertSessionHasErrors([
            'test_string' => 'The test string field is required.',
            'test_int' => 'The test int must be an integer.',
        ]);

        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => 'short',
            'test_int' => '1',
        ]);

        $response->assertSessionHasErrors([
            'test_string' => 'The test string must be at least 10 characters.',
            'test_int' => 'The test int must be at least 10.',
        ]);
    }

    /** @test */
    public function passing_scalar_validation (): void
    {
        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => 'some string',
            'test_int' => '11',
            'test_object' => 'test entity',
        ]);

        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function getting_the_scalar_data (): void
    {
        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => $string = 'some string',
            'test_int' => $int = '11',
            'test_object' => 'test entity',
        ]);

        /** @var \Illuminate\Support\Collection $data */
        $data = $response->getOriginalContent()['scalar'];

        $this->assertSame($string, $data->get('test_string'));
        $this->assertSame($int, $data->get('test_int'));
    }

    /** @test */
    public function failing_object_validation (): void
    {
        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => 'some string',
            'test_int' => '11',
            'test_object' => 'this should fail',
        ]);

        $response->assertSessionHasErrors([
            'test_object' => 'RuleStub failed.',
        ]);
    }

    /** @test */
    public function passing_object_validation (): void
    {
        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => 'some string',
            'test_int' => '11',
            'test_object' => 'test entity',
        ]);

        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function getting_the_object_stage_data (): void
    {
        $this->withoutExceptionHandling();

        $response = $this->call('POST', self::ENDPOINT, [
            'test_string' => 'some string',
            'test_int' => '11',
            'test_object' => 'test entity',
        ]);

        $response->assertOk();

        $data = $response->getOriginalContent()['object'];

        $this->assertInstanceOf(TestEntity::class, $data->get('test_object_cached'));
    }
}
