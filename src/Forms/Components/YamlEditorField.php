<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use JeffersonGoncalves\FilamentYamlEditor\Rules\ValidYaml;
use Symfony\Component\Yaml\Yaml;

class YamlEditorField extends Field
{
    /** @var view-string */
    protected string $view = 'filament-yaml-editor::forms.components.yaml-editor-field';

    protected int|Closure $height = 300;

    protected int|Closure|null $minLines = null;

    protected bool|Closure $withToolbar = false;

    protected bool|Closure $castState = false;

    protected bool|Closure $autoFormat = false;

    protected ?string $theme = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (self $component, $state): void {
            if ($component->evaluate($component->castState) && is_array($state)) {
                $component->state(Yaml::dump($state, 4, 2));
            }
        });

        $this->dehydrateStateUsing(function ($state) {
            if ($this->evaluate($this->castState)) {
                if (is_string($state) && $state !== '') {
                    return Yaml::parse($state) ?? [];
                }

                return is_array($state) ? $state : [];
            }

            return $state;
        });
    }

    public function height(int|Closure $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function minLines(int|Closure|null $minLines): static
    {
        $this->minLines = $minLines;

        return $this;
    }

    public function withToolbar(bool|Closure $condition = true): static
    {
        $this->withToolbar = $condition;

        return $this;
    }

    public function castState(bool|Closure $condition = true): static
    {
        $this->castState = $condition;

        return $this;
    }

    public function readOnly(bool|Closure $condition = true): static
    {
        $this->isDisabled = $condition;

        return $this;
    }

    public function dark(): static
    {
        $this->theme = 'dark';

        return $this;
    }

    public function light(): static
    {
        $this->theme = 'light';

        return $this;
    }

    public function autoFormat(bool|Closure $condition = true): static
    {
        $this->autoFormat = $condition;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->evaluate($this->height);
    }

    public function getMinLines(): ?int
    {
        return $this->evaluate($this->minLines);
    }

    public function hasToolbar(): bool
    {
        return $this->evaluate($this->withToolbar);
    }

    public function isReadOnly(): bool
    {
        return $this->isDisabled();
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function shouldAutoFormat(): bool
    {
        return $this->evaluate($this->autoFormat);
    }

    /**
     * @param  string|array<mixed>|Closure  $rules
     */
    public function rules(string|array|Closure $rules, bool|Closure $condition = true): static
    {
        if (is_array($rules) && in_array('yaml', $rules, true)) {
            $rules = array_filter($rules, fn ($rule) => $rule !== 'yaml');
            $rules[] = new ValidYaml;
        }

        return parent::rules($rules, $condition);
    }
}
