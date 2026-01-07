@echo off
setlocal enabledelayedexpansion

for /f "delims=" %%v in ('php -r "echo PHP_VERSION;"') do set "PHP_VERSION=%%v"

if "%PHP_VERSION:~0,3%"=="8.0" (
    echo PHP 8.0 found adapt composer.json ...

    powershell -NoProfile -Command ^
      "(Get-Content composer.json) -replace '\"sweetrdf/json-ld\":.*', '\"ml/json-ld\": \"^1.2\"' | Set-Content composer.json"

    echo composer.json adapted
)
