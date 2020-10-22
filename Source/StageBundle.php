<?php

namespace Dbt\StagedValidation;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

abstract class StageBundle implements StageBundleInterface
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var \Illuminate\Contracts\Validation\Factory */
    protected $factory;

    /** @var \Illuminate\Support\Collection */
    protected $payloads;

    public function __construct (Request $request, Factory $factory)
    {
        $this->request = $request;
        $this->factory = $factory;
        $this->payloads = new Collection([]);
    }

    /**
     * The stages to validate.
     * @return \Dbt\StagedValidation\StageInterface[]
     */
    abstract public function stages (): array;

    /**
     * Get the given stage's validated data.
     */
    public function get (string $name): Collection
    {
        return $this->payloads->get($name);
    }

    /**
     * Get all stages' validated data.
     */
    public function all (): Collection
    {
        return $this->payloads;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateResolved ()
    {
        foreach ($this->stages() as $stage) {
            $this->validateStage($stage);
        }
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateStage (StageInterface $stage): void
    {
        $this->factory->resolver(
            $stage->validatorResolver()
        );

        /** @var \Dbt\StagedValidation\StageValidator $validator */
        $validator = $this->factory->make(
            $this->request->all(),
            $stage->rules()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $stageData = Collection::make($validator->validated())->merge(
            $validator->getCached()
        );

        $this->payloads->put($stage->name(), $stageData);
    }
}
