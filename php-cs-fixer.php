<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('vendor')
    ->in(['src', 'tests'])
    ->name('*.php')
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    'binary_operator_spaces' => ['operators' => ['=>' => 'align']],
    'concat_space' => ['spacing' => 'one'],
    'not_operator_with_space' => false,
    'not_operator_with_successor_space' => false,
    'single_line_throw' => false,
])
    ->setFinder($finder)
    ->setCacheFile('.dev/tools/php-cs-fixer/.php-cs-fixer.cache');
