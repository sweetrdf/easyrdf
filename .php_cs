<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'phpdoc_summary' => false,
     ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->files()
        ->in(__DIR__ .'/examples')
        ->in(__DIR__ .'/lib')
        ->in(__DIR__ .'/test')
        ->name('*.php')
    );
