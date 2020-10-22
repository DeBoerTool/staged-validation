<?php

namespace Dbt\StagedValidation\Tests\Infra\Stubs;

use Dbt\StagedValidation\StageBundle;

class StageBundleStub extends StageBundle
{
    /**
     * @return \Dbt\StagedValidation\StageInterface[]
     */
    public function stages (): array
    {
        return [
            new ScalarStageStub(),
            new ObjectStageStub(),
        ];
    }
}
