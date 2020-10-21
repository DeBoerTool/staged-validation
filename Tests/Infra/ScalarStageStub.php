<?php

namespace Dbt\StagedValidation\Tests\Infra;

use Dbt\StagedValidation\ScalarStage;

class ScalarStageStub implements ScalarStage
{
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
            ]
        ];
    }
}
