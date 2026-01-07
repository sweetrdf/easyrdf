@echo off
setlocal enabledelayedexpansion

for /f "delims=" %%v in ('php -r "echo PHP_VERSION;"') do set "php_version=%%v"

echo %php_version% | findstr /b "8.0" >nul
if %errorlevel% equ 0 (
    echo PHP 8.0 found - adjusting composer.json...

    set "temp_file=%temp%\composer_temp.json"
    (
        for /f "tokens=*" %%a in (composer.json) do (
            set "line=%%a"
            echo !line! | findstr /i /c:"\"sweetrdf/json-ld\"" >nul
            if !errorlevel! equ 0 (
                echo     "ml/json-ld": "^1.2"
            ) else (
                echo !line!
            )
        )
    ) > %temp_file%

    move /Y %temp_file% composer.json >nul

    echo composer.json adjusted: ml/json-ld is now used.
) else (
    echo PHP version is not 8.0 - no adjustment needed.
)
