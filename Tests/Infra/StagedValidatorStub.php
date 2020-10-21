<?php

namespace Dbt\StagedValidation\Tests\Infra;

use Dbt\StagedValidation\StagedValidator;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

class StagedValidatorStub implements StagedValidator
{
    /** @var \Illuminate\Http\Request */
    private $request;

    /** @var \Illuminate\Contracts\Validation\Factory */
    private $factory;

    public function __construct (Request $request, Factory $factory)
    {
        $this->request = $request;
        $this->factory = $factory;
    }

    /**
     * @return \Dbt\StagedValidation\ProvidesRules[]
     */
    public function stages (): array
    {
        return [
            new ScalarStageStub(),
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateResolved ()
    {
        foreach ($this->stages() as $stage) {
            $validator = $this->factory->make(
                $this->request->all(),
                $stage->rules()
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }
}
