import { defineConfig } from 'vite';
import gems from './resource/vite-plugin';

export default defineConfig({
    plugins: [
        gems({
            input: [
                'resource/js/vue-respondent.js',
                'resource/js/jquery.js',
            ],
            refresh: true,
            publicHost: 'gemstracker.test',
        }),
    ],
});