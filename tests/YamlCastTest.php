<?php

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentYamlEditor\Casts\YamlCast;

beforeEach(function () {
    $this->cast = new YamlCast;
    $this->model = new class extends Model {};
});

it('casts yaml string to array on get', function () {
    $yaml = "name: test\nversion: 1";
    $result = $this->cast->get($this->model, 'config', $yaml, []);

    expect($result)->toBe(['name' => 'test', 'version' => 1]);
});

it('returns null for null value on get', function () {
    $result = $this->cast->get($this->model, 'config', null, []);

    expect($result)->toBeNull();
});

it('returns null for empty string on get', function () {
    $result = $this->cast->get($this->model, 'config', '', []);

    expect($result)->toBeNull();
});

it('casts array to yaml string on set', function () {
    $array = ['name' => 'test', 'version' => 1];
    $result = $this->cast->set($this->model, 'config', $array, []);

    expect($result)->toContain('name: test')
        ->and($result)->toContain('version: 1');
});

it('returns null for null value on set', function () {
    $result = $this->cast->set($this->model, 'config', null, []);

    expect($result)->toBeNull();
});

it('passes through string value on set', function () {
    $yaml = "name: test\nversion: 1";
    $result = $this->cast->set($this->model, 'config', $yaml, []);

    expect($result)->toBe($yaml);
});

it('handles nested arrays on set', function () {
    $array = [
        'database' => [
            'host' => 'localhost',
            'port' => 3306,
        ],
    ];
    $result = $this->cast->set($this->model, 'config', $array, []);

    expect($result)->toContain('database:')
        ->and($result)->toContain('host: localhost')
        ->and($result)->toContain('port: 3306');
});
