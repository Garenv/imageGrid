import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
      baseUrl: 'http://phopixel.test',
      setupNodeEvents(on, config) {},
  },
});
