<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Tables\Columns;

use Filament\Tables\Columns\Column;
use Symfony\Component\Yaml\Yaml;

class YamlEditorColumn extends Column
{
    /** @var view-string */
    protected string $view = 'filament-yaml-editor::tables.columns.yaml-editor-column';

    protected ?string $theme = null;

    protected int $modalHeight = 400;

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

    public function modalHeight(int $height): static
    {
        $this->modalHeight = $height;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function getModalHeight(): int
    {
        return $this->modalHeight;
    }

    public function getFormattedState(): ?string
    {
        $state = $this->getState();

        if (blank($state)) {
            return null;
        }

        if (is_array($state)) {
            return Yaml::dump($state, 4, 2);
        }

        return (string) $state;
    }
}
