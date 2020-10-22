<?php

namespace Dbt\StagedValidation;

use Closure;
use Illuminate\Contracts\Translation\Translator;

abstract class Stage implements StageInterface
{
    abstract public function rules (): array;

    abstract public function name (): string;

    public function validatorResolver (): Closure
    {
        return function (
            Translator $translator,
            array $data,
            array $rules,
            array $messages = [],
            array $customAttributes = []
        ) {
            return new StageValidator(
                $translator,
                $data,
                $rules,
                $messages,
                $customAttributes
            );
        };
    }
}
