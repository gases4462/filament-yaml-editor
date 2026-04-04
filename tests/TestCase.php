<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Tests;

use JeffersonGoncalves\FilamentYamlEditor\YamlEditorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            YamlEditorServiceProvider::class,
        ];
    }
}
