import SeederTool from './components/SeederTool';
import modelsData from './data/models-generated.json';

export const metadata = {
  title: 'Seeder AutoFill Tool',
  description: 'Generate seeder run scripts for BulkUpdateFromText',
};

/** Try loading models from Neon DB; fall back to build-time JSON. */
async function loadModels(): Promise<Record<string, string[]>> {
  if (!process.env.DATABASE_URL) {
    return modelsData as Record<string, string[]>;
  }
  try {
    // Dynamic import so a missing DATABASE_URL doesn't break the static build.
    const { getAllModels } = await import('../lib/db');
    const dbModels = await getAllModels();
    // If the table is empty fall back to the JSON so the page is always useful.
    return Object.keys(dbModels).length > 0
      ? dbModels
      : (modelsData as Record<string, string[]>);
  } catch {
    return modelsData as Record<string, string[]>;
  }
}

export default async function Home() {
  const models = await loadModels();
  const count = Object.keys(models).length;
  return <SeederTool initialModels={models} modelCount={count} />;
}
