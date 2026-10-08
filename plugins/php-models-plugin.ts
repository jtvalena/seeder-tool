import type { Plugin } from 'vite';
import { readFileSync, readdirSync, statSync } from 'fs';
import { join, basename } from 'path';

// ── Helpers ────────────────────────────────────────────────
function walkPhp(dir: string): string[] {
  const results: string[] = [];
  try {
    for (const entry of readdirSync(dir, { withFileTypes: true })) {
      const full = join(dir, entry.name);
      if (entry.isDirectory()) {
        results.push(...walkPhp(full));
      } else if (entry.name.endsWith('.php')) {
        results.push(full);
      }
    }
  } catch { /* skip unreadable dirs */ }
  return results;
}

function parseFillable(content: string): string[] {
  const match = content.match(/protected\s+\$fillable\s*=\s*\[([\s\S]*?)\];/);
  if (!match) return [];
  const cols: string[] = [];
  const re = /'([^']+)'/g;
  let m: RegExpExecArray | null;
  while ((m = re.exec(match[1])) !== null) cols.push(m[1]);
  return cols;
}

// ── Plugin ─────────────────────────────────────────────────
export function phpModelsPlugin(referencesDir: string): Plugin {
  const VIRTUAL_ID = 'virtual:php-models';
  const RESOLVED_ID = '\0' + VIRTUAL_ID;

  return {
    name: 'php-models-plugin',

    resolveId(id) {
      if (id === VIRTUAL_ID) return RESOLVED_ID;
    },

    load(id) {
      if (id !== RESOLVED_ID) return;

      const phpFiles = walkPhp(referencesDir);
      const models: Record<string, string[]> = {};

      for (const file of phpFiles) {
        const name = basename(file, '.php');
        try {
          const cols = parseFillable(readFileSync(file, 'utf-8'));
          if (cols.length > 0) models[name] = cols;
        } catch { /* skip unreadable files */ }
      }

      const count = Object.keys(models).length;
      console.log(`[php-models-plugin] parsed ${count} models from ${phpFiles.length} PHP files`);

      return `export const MODELS_FROM_PHP = ${JSON.stringify(models)};
export const MODEL_COUNT = ${count};`;
    },

    // HMR: invalidate when any PHP file in the references dir changes
    configureServer(server) {
      server.watcher.add(referencesDir);
      server.watcher.on('change', (file) => {
        if (file.endsWith('.php') && file.includes('references')) {
          const mod = server.moduleGraph.getModuleById(RESOLVED_ID);
          if (mod) server.moduleGraph.invalidateModule(mod);
        }
      });
    },
  };
}
