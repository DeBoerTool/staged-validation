<?php

namespace Dbt\StagedValidation\Tests\Infra\Stubs;

use Dbt\StagedValidation\CachedRule;
use Dbt\StagedValidation\Tests\Infra\TestEntity;
use Dbt\StagedValidation\Tests\Suites\Feature\TestCase;

class RuleStub extends CachedRule
{
    public function message ()
    {
        return 'RuleStub failed.';
    }

    public function fetch ()
    {
        TestCase::$called++;

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
