import { defineConfig } from 'vite';
import gems from './resource/vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        gems({
            input: [
                //'resource/js/vue-respondent.js',
                'resource/js/gems-vue.js',
                'resource/js/jquery.js',
            ],
            refresh: true,
            publicHost: 'gemstracker.test',
        }),
        vue(),
    ],
});