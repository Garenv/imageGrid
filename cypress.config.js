import { defineConfig } from "cypress";
export default defineConfig({
  e2e: {
      baseUrl: 'http://phopixel.test',
      setupNodeEvents(on, config) {},
      supportFile: './cypress/support/index.js',
      viewportWidth: 1366,
      viewportHeight: 768,
  },
});
