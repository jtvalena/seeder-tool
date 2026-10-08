import SeederTool from './components/SeederTool';
import modelsData from './data/models-generated.json';

export const metadata = {
  title: 'Seeder AutoFill Tool',
  description: 'Generate seeder run scripts for BulkUpdateFromText',
};

export default function Home() {
  const models = modelsData as Record<string, string[]>;
  const count = Object.keys(models).length;
  return <SeederTool initialModels={models} modelCount={count} />;
}
