<?php

use Dbt\PhpCsFixerConfig\Loader;
use PhpCsFixer\Finder;

$finder = Finder::create()->in([
    __DIR__.'/Source',
    __DIR__.'/Tests',
]);

$overrides = [
    'php_unit_test_class_requires_covers' => false,
];

return Loader::new($finder, $overrides)->getConfig();
