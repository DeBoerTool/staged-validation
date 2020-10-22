<?php

namespace Dbt\StagedValidation;

interface PayloadInterface
{
    public function all (): array;
    public function set (string $key, $value): void;
    public function get (string $key);
}
