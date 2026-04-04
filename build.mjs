import esbuild from 'esbuild'
import { execSync } from 'child_process'
import fs from 'fs'

const isWatch = process.argv.includes('--watch')

// Ensure dist directory exists
if (!fs.existsSync('resources/dist')) {
    fs.mkdirSync('resources/dist', { recursive: true })
}

// Build JS with esbuild
const buildOptions = {
    entryPoints: ['resources/js/yaml-editor.js'],
    outfile: 'resources/dist/yaml-editor.js',
    bundle: true,
    minify: !isWatch,
    format: 'iife',
    target: ['es2020'],
    define: {
        'process.env.NODE_ENV': isWatch ? '"development"' : '"production"',
    },
}

if (isWatch) {
    const ctx = await esbuild.context(buildOptions)
    await ctx.watch()
    console.log('Watching for changes...')
} else {
    await esbuild.build(buildOptions)
    console.log('JS built successfully.')
}

// Build CSS with PostCSS
try {
    execSync('npx postcss resources/css/yaml-editor.css -o resources/dist/yaml-editor.css', {
        stdio: 'inherit',
    })
    console.log('CSS built successfully.')
} catch (e) {
    console.error('CSS build failed:', e.message)
}
