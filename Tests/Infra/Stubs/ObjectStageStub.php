<?php

namespace Dbt\StagedValidation\Tests\Infra\Stubs;

use Dbt\StagedValidation\Stage;

class ObjectStageStub extends Stage
{
    public function name (): string
    {
        return 'object';
    }

    public function rules (): array
    {
        return [
            'test_object' => [
                'required',
                new RuleStub(),
            ],
        ];
    }
}
