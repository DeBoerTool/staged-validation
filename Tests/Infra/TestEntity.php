<?php

namespace Dbt\StagedValidation\Tests\Infra;

class TestEntity
{
    /** @var string */
    private $string;

    public function __construct (string $string)
    {
        $this->string = $string;
    }

    public function get (): string
    {
        return $this->string;
    }
}
