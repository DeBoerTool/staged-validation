<?php

namespace Dbt\StagedValidation;

abstract class CachedRule implements CachedRuleInterface
{
    protected $cached;

    abstract public function message ();
    abstract protected function fetch ();
    abstract protected function passesAfterFetch ($attribute, $value);

    public function passes ($attribute, $value)
    {
        $this->setCached();

        return $this->passesAfterFetch($attribute, $value);
    }

    public function get ()
    {
        return $this->cached;
    }

    protected function setCached (): void
    {
        $this->cached = $this->fetch();
    }
}
