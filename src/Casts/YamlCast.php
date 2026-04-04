<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

/**
 * @implements CastsAttributes<array<array-key, mixed>, array<array-key, mixed>|string>
 */
class YamlCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return Yaml::parse($value) ?? [];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            return Yaml::dump($value, 4, 2);
        }

        return (string) $value;
    }
}
