<?php

namespace Dbt\StagedValidation;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;

interface StagedValidator extends ValidatesWhenResolved
{
    public function stages (): array;
}
