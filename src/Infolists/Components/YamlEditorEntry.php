<?php

namespace JeffersonGoncalves\FilamentYamlEditor\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Symfony\Component\Yaml\Yaml;

class YamlEditorEntry extends Entry
{
    /** @var view-string */
    protected string $view = 'filament-yaml-editor::infolists.components.yaml-editor-entry';

    protected int $height = 300;

    protected ?string $theme = null;

    public function height(int $height): static
    {
        $this->height = $height;

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

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
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
