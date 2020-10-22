<?php

namespace Dbt\StagedValidation;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Support\Collection;

interface StageBundleInterface extends ValidatesWhenResolved
{
    public function stages (): array;
    public function get (string $name): Collection;
    public function all (): Collection;
}
