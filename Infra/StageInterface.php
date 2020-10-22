<?php

namespace Dbt\StagedValidation;

use Closure;

interface StageInterface
{
    public function name (): string;
    public function rules (): array;
    public function validatorResolver (): Closure;
}
