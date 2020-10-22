<?php

namespace Dbt\StagedValidation;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

class StageValidator extends Validator
{
    /** @var \Illuminate\Support\Collection */
    protected $cached;

    public function __construct (
        Translator $translator,
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        parent::__construct(
            $translator,
            $data,
            $rules,
            $messages,
            $customAttributes
        );

        $this->cached = new Collection([]);
    }

    public function getCached (): Collection
    {
        return $this->cached;
    }

    /**
     * Override the base
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Illuminate\Contracts\Validation\Rule  $rule
     * @return void
     */
    protected function validateUsingCustomRule ($attribute, $value, $rule): void
    {
        if (! $rule->passes($attribute, $value)) {
            $this->failedRules[$attribute][get_class($rule)] = [];

            $messages = $rule->message() ? (array) $rule->message() : [get_class($rule)];

            foreach ($messages as $message) {
                $this->messages->add($attribute, $this->makeReplacements(
                    $message, $attribute, get_class($rule), []
                ));
            }
        }

        if ($rule instanceof CachedRuleInterface) {
            $this->cached->put($attribute . '_cached', $rule->get());
        }
    }
}
