<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ValidYaml implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || blank($value)) {
            return;
        }

        try {
            Yaml::parse($value);
        } catch (ParseException $e) {
            $fail("The :attribute field contains invalid YAML: {$e->getMessage()}");
        }
    }
}
