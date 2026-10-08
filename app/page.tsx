import SeederTool from './components/SeederTool';
import { MODELS_FROM_PHP, MODEL_COUNT } from 'virtual:php-models';

export const metadata = {
  title: 'Seeder AutoFill Tool',
  description: 'Generate seeder run scripts for BulkUpdateFromText',
};

export default function Home() {
  return <SeederTool initialModels={MODELS_FROM_PHP} modelCount={MODEL_COUNT} />;
}
