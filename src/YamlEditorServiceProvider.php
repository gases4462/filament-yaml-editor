<?php

namespace JeffersonGoncalves\FilamentYamlEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlEditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-yaml-editor';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('yaml-editor', __DIR__.'/../resources/dist/yaml-editor.js'),
            Css::make('yaml-editor-styles', __DIR__.'/../resources/dist/yaml-editor.css'),
        ], package: 'jeffersongoncalves/filament-yaml-editor');

        Validator::extend('yaml', function (string $attribute, mixed $value): bool {
            if (! is_string($value) || blank($value)) {
                return true;
            }

            try {
                Yaml::parse($value);

                return true;
            } catch (ParseException) {
                return false;
            }
        }, 'The :attribute field contains invalid YAML.');
    }
}
