<?php

namespace Dbt\StagedValidation;

use Illuminate\Contracts\Validation\Rule;

interface CachedRuleInterface extends Rule
{
    public function get ();
}
