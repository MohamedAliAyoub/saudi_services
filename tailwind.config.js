import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './visit-resource/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
