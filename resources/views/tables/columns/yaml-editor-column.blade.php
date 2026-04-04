@php
    $state = $getFormattedState();
    $theme = $getTheme();
    $modalHeight = $getModalHeight();
    $columnLabel = $getLabel() ?? $getName();
@endphp

<div>
    @if ($state)
        <div
            x-data="{ open: false }"
        >
            <button
                type="button"
                x-on:click="open = true"
                class="yaml-editor-column-btn"
            >
                <x-filament::icon icon="heroicon-o-code-bracket" class="yaml-editor-column-btn-icon" />
                <span>View YAML</span>
            </button>

            <template x-teleport="body">
                <div
                    x-show="open"
                    x-on:keydown.escape.window="open = false"
                    x-transition:enter="yaml-editor-modal-enter"
                    x-transition:enter-start="yaml-editor-modal-enter-start"
                    x-transition:enter-end="yaml-editor-modal-enter-end"
                    x-transition:leave="yaml-editor-modal-leave"
                    x-transition:leave-start="yaml-editor-modal-leave-start"
                    x-transition:leave-end="yaml-editor-modal-leave-end"
                    class="yaml-editor-modal-overlay"
                    style="display: none;"
                >
                    <div class="yaml-editor-modal-backdrop" x-on:click="open = false"></div>

                    <div class="yaml-editor-modal-container">
                        <div class="yaml-editor-modal-panel">
                            <div class="yaml-editor-modal-header">
                                <h3 class="yaml-editor-modal-title">{{ $columnLabel }}</h3>
                                <button
                                    type="button"
                                    x-on:click="open = false"
                                    class="yaml-editor-modal-close"
                                >
                                    <x-filament::icon icon="heroicon-o-x-mark" class="yaml-editor-modal-close-icon" />
                                </button>
                            </div>

                            <div class="yaml-editor-modal-body">
                                <div
                                    ax-load
                                    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('yaml-editor', 'jeffersongoncalves/filament-yaml-editor') }}"
                                    x-data="yamlEditor({
                                        state: @js($state),
                                        readOnly: true,
                                        height: @js($modalHeight),
                                        toolbar: false,
                                        theme: @js($theme ?? 'auto'),
                                        autoFormat: false,
                                    })"
                                    x-ref="root"
                                    class="yaml-editor-wrapper"
                                >
                                    <div x-ref="editor" class="yaml-editor-container" style="min-height: {{ $modalHeight }}px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @else
        <span class="yaml-editor-column-empty">&mdash;</span>
    @endif
</div>
