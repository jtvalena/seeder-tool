// Runs before any build. Reads $fillable arrays from PHP reference models
// and writes app/data/models-generated.json for static import.

import { readFileSync, readdirSync, writeFileSync, mkdirSync } from 'fs';
import { join, basename, dirname } from 'path';
import { fileURLToPath } from 'url';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');

// Try multiple locations: current repo layout first, then legacy paths
const REFS_DIR = (() => {
  const candidates = [
    join(ROOT, 'seeder-tool', 'references'),             // current layout (inside repo)
    join(ROOT, 'seeder-tool-app', 'references'),         // legacy inside-repo path
    join(ROOT, '..', 'seeder-tool', 'references'),       // sibling dir on dev machine
    join(ROOT, '..', 'seeder-tool-app', 'references'),   // legacy sibling dir on dev machine
  ];
  for (const c of candidates) {
    try { readdirSync(c); return c; } catch { /* not found */ }
  }
  return null;
})();
const OUT_FILE = join(ROOT, 'app', 'data', 'models-generated.json');

if (!REFS_DIR) {
  // No PHP references found — write empty object as fallback
  mkdirSync(dirname(OUT_FILE), { recursive: true });
  writeFileSync(OUT_FILE, '{}');
  console.warn('[generate-models] References directory not found. Writing empty models.');
  process.exit(0);
}

function walkPhp(dir) {
  const results = [];
  try {
    for (const entry of readdirSync(dir, { withFileTypes: true })) {
      const full = join(dir, entry.name);
      if (entry.isDirectory()) results.push(...walkPhp(full));
      else if (entry.name.endsWith('.php')) results.push(full);
    }
  } catch { /* skip */ }
  return results;
}

function parseFillable(content) {
  const match = content.match(/protected\s+\$fillable\s*=\s*\[([\s\S]*?)\];/);
  if (!match) return [];
  const cols = [];
  const re = /'([^']+)'/g;
  let m;
  while ((m = re.exec(match[1])) !== null) cols.push(m[1]);
  return cols;
}

const files = walkPhp(REFS_DIR);
const models = {};

for (const file of files) {
  try {
    const name = basename(file, '.php');
    const cols = parseFillable(readFileSync(file, 'utf-8'));
    if (cols.length > 0) models[name] = cols;
  } catch { /* skip unreadable */ }
}

mkdirSync(dirname(OUT_FILE), { recursive: true });
writeFileSync(OUT_FILE, JSON.stringify(models));
console.log(`[generate-models] ${Object.keys(models).length} models from ${files.length} PHP files → app/data/models-generated.json`);
