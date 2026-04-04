import { basicSetup } from 'codemirror'
import { EditorView, keymap } from '@codemirror/view'
import { EditorState } from '@codemirror/state'
import { yaml } from '@codemirror/lang-yaml'
import { linter, lintGutter } from '@codemirror/lint'
import { oneDark } from '@codemirror/theme-one-dark'
import { indentWithTab } from '@codemirror/commands'
import { load as yamlLoad, dump as yamlDump } from 'js-yaml'

export default function yamlEditor({ state, readOnly, height, toolbar, theme, autoFormat }) {
    return {
        state,
        editorView: null,
        isFullscreen: false,

        init() {
            const self = this

            const yamlLinter = linter((view) => {
                const diagnostics = []
                try {
                    yamlLoad(view.state.doc.toString())
                } catch (e) {
                    if (e.mark) {
                        const pos = Math.min(e.mark.position, view.state.doc.length)
                        diagnostics.push({
                            from: pos,
                            to: Math.min(pos + 1, view.state.doc.length),
                            severity: 'error',
                            message: e.reason || e.message,
                        })
                    }
                }
                return diagnostics
            })

            const extensions = [
                basicSetup,
                yaml(),
                yamlLinter,
                lintGutter(),
                keymap.of([indentWithTab]),
                EditorView.updateListener.of((update) => {
                    if (update.docChanged) {
                        self.state = update.state.doc.toString()
                    }
                }),
                EditorView.editable.of(!readOnly),
                EditorState.readOnly.of(readOnly),
                EditorView.theme({
                    '.cm-editor': {
                        height: height + 'px',
                        overflow: 'auto',
                    },
                    '.cm-scroller': {
                        overflow: 'auto',
                    },
                }),
            ]

            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
            const filamentDark = document.documentElement.classList.contains('dark')
            const useDark = theme === 'dark' || (theme === 'auto' && (filamentDark || prefersDark))

            if (useDark) {
                extensions.push(oneDark)
            }

            this.editorView = new EditorView({
                state: EditorState.create({
                    doc: this.state ?? '',
                    extensions,
                }),
                parent: this.$refs.editor,
            })

            if (autoFormat) {
                this.$refs.editor.addEventListener('focusout', () => {
                    this.format()
                })
            }

            this.$watch('state', (value) => {
                const current = this.editorView.state.doc.toString()
                if (value !== current) {
                    this.editorView.dispatch({
                        changes: {
                            from: 0,
                            to: current.length,
                            insert: value ?? '',
                        },
                    })
                }
            })
        },

        format() {
            try {
                const doc = this.editorView.state.doc.toString()
                if (!doc.trim()) return

                const parsed = yamlLoad(doc)
                const formatted = yamlDump(parsed, {
                    indent: 2,
                    lineWidth: 120,
                    noRefs: true,
                })

                this.editorView.dispatch({
                    changes: {
                        from: 0,
                        to: this.editorView.state.doc.length,
                        insert: formatted,
                    },
                })
            } catch (_) {
                // Invalid YAML, skip formatting
            }
        },

        copy() {
            const text = this.editorView.state.doc.toString()
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text)
            }
        },

        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen
            this.$refs.root.classList.toggle('yaml-editor--fullscreen', this.isFullscreen)

            if (this.isFullscreen) {
                this.editorView.dispatch({})
            }
        },

        destroy() {
            this.editorView?.destroy()
        },
    }
}
