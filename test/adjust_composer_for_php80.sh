#!/bin/bash

if [[ "$(php -r 'echo PHP_VERSION;')" == 8.0* ]]; then
    echo "PHP 8.0 found adapt composer.json ..."

    sed -i 's/"sweetrdf\/json-ld":.*/"ml\/json-ld": "^1.2",/' composer.json

    echo "composer.json adapted"
fi
