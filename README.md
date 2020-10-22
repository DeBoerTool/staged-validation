# Staged Validation for Laravel

This package provides an easy way to validate request data in stages. This is useful in cases where you want to validate, say, scalar data before doing heavy lifting against the database or external services.

This package also provides a convenient way to cache data in your `Rule` classes, so you don't have to look up records twice (eg once in a form request and again in a controller).

## Installation

```bash
composer require dbt/staged-validation
```

## Usage

### Bundles

Define a set of stages by extending the abstract `StageBundle`. It should look something like this:

```php
use Dbt\StagedValidation\StageBundle;

class MyBundle extends StageBundle
{
    /**
     * @return \Dbt\StagedValidation\StageInterface[]
     */
    public function stages (): array
    {
        return [
            new FirstStage(),
            new SecondStage(),
        ];
    }
}
```

### Stages

Each stage should extend the abstract `Stage` class. Eg:

```php
use Dbt\StagedValidation\Stage;

class FirstStage extends Stage
{
    public function name (): string
    {
        return 'first_stage';
    }

    public function rules (): array
    {
        return [
            'my_string' => [
                'required',
                'string',
                'min:10',
            ],
        ];
    }
}
```

### Validation

Simply typehint the bundle in your controller:

```php
class MyController {
    public function __invoke (MyBundle $bundle) {
        // Get all the validated data from each stage.
        $all = $bundle->all();

        // Get the validated data from a specific stage.
        $first = $bundle->get('first_stage');
    }
}
```

Each stage will be validated separately. This means if one stage fails validation, the next stage won't be run.

### Retaining entities

If you wish to retain fetched entities, extend the `CachedRule` class. It's simply a Laravel `Rule` with a slightly different interface:

```php
use Dbt\StagedValidation\CachedRule;

class RuleStub extends CachedRule
{
    public function message ()
    {
        return 'my failure message';
    }

    public function fetch ()
    {
        // Fetch from an external resource. Under the hood, whatever you
        // return here will be assigned to $this->cached.
    }

    protected function passesAfterFetch ($attribute, $value)
    {
        // Perform validation as you normally would, keeping in mind that
        // $this->cached has already been assigned.
        return $this->cached->someMethod() === $value;
    }
}
```

The retained entities will be serialized along with the original values as part of the bundle payload. So if a stage named `second_stage` has a cached rule for an attribute called `my_attribute`, you will be see a collection with this shape:

```php
// $bundle->get('second_stage')->toArray()
[
    'second_stage' => [
        'my_attribute' => // the original data,
        'my_attribute_cached' => // the fetched entity
    ],
];
```

## Extending

If you wish to write your own implementation, you may implement any of the provided interfaces: `CachedRuleInterface`, `StageBundleInterface`, or `StageInterface`.

If you wish to change the Validator implementation for a given stage, you may override the `Stage::validatorResolver()` method.

## Etc.

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
