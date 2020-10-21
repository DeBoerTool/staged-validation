<?php

namespace Dbt\StagedValidation;

interface ObjectValidator
{
    public function rules (): array;

    public function payload (): ObjectPayload;
}
