import { defineConfig } from "vite";
import vinext from "vinext";
import { cloudflare } from "@cloudflare/vite-plugin";
import { responseStoreServiceBinding } from "./cloudflare.config";
import { responseStoreAdapter } from "@vinext/cloudflare/cache/response-store-adapter";
import { imagesOptimizer } from "@vinext/cloudflare/images/images-optimizer";
import { phpModelsPlugin } from "./plugins/php-models-plugin";
import { resolve } from "path";

export default defineConfig({
  plugins: [
    phpModelsPlugin(resolve(__dirname, "seeder-tool-app/references")),
    vinext({
      cache: responseStoreAdapter(),
      prerender: { routes: "*" },
      images: { optimizer: imagesOptimizer() },
    }),
    cloudflare({
      auxiliaryWorkers: [{ config: responseStoreServiceBinding }],
      viteEnvironment: {
        name: "rsc",
        childEnvironments: ["ssr"],
      },
    }),
  ],
});
