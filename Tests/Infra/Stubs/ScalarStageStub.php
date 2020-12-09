<?php

namespace Dbt\StagedValidation\Tests\Infra\Stubs;

use Dbt\StagedValidation\Stage;

class ScalarStageStub extends Stage
{
    public function name (): string
    {
        return 'scalar';
    }

    public function rules (): array
    {
        return [
            'test_string' => [
                'required',
                'string',
                'min:10',
            ],
            'test_int' => [
                'integer',
                'min:10',
            ],
        ];
    }
}
