<?php

use JeffersonGoncalves\FilamentYamlEditor\Rules\ValidYaml;

it('passes for valid yaml', function () {
    $rule = new ValidYaml;
    $failed = false;

    $rule->validate('config', "name: test\nversion: 1", function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('fails for invalid yaml', function () {
    $rule = new ValidYaml;
    $message = null;

    $rule->validate('config', "name: test\n  invalid: indentation\n bad", function ($msg) use (&$message) {
        $message = $msg;
    });

    expect($message)->toContain('invalid YAML');
});

it('passes for empty string', function () {
    $rule = new ValidYaml;
    $failed = false;

    $rule->validate('config', '', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('passes for null value', function () {
    $rule = new ValidYaml;
    $failed = false;

    $rule->validate('config', null, function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('passes for complex nested yaml', function () {
    $rule = new ValidYaml;
    $failed = false;

    $yaml = <<<'YAML'
    database:
      host: localhost
      port: 3306
      credentials:
        username: admin
        password: secret
      options:
        - read
        - write
    YAML;

    $rule->validate('config', $yaml, function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});
