/* eslint-disable import/no-extraneous-dependencies */
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import gems from './resource/vite-plugin';

export default defineConfig({
  plugins: [
    gems({
      input: [
        // 'resource/js/vue-respondent.js',
        'resource/js/gems-vue.js',
        'resource/js/jquery.js',
      ],
      refresh: true,
      publicHost: 'gemstracker.test',
    }),
    vue(),
  ],
});
