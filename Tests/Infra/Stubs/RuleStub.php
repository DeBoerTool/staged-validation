<?php

namespace Dbt\StagedValidation\Tests\Infra\Stubs;

use Dbt\StagedValidation\CachedRule;
use Dbt\StagedValidation\Tests\Infra\TestEntity;

class RuleStub extends CachedRule
{
    public function message ()
    {
        return 'RuleStub failed.';
    }

    public function fetch ()
    {
        /**
         * This could be fetching from the database, fetching from an external
         * service, etc.
         */
        return new TestEntity('test entity');
    }

    protected function passesAfterFetch ($attribute, $value)
    {
        return $this->cached->get() === $value;
    }
}
