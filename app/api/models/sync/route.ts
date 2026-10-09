import { NextResponse } from 'next/server';
import { syncModels } from '@/lib/db';
import modelsJson from '@/app/data/models-generated.json';

/**
 * POST /api/models/sync
 * Seeds the DB from the build-time models-generated.json.
 * Call once after deploying to populate the models table from PHP references.
 */
export async function POST() {
  try {
    const models = modelsJson as Record<string, string[]>;
    await syncModels(models);
    const count = Object.keys(models).length;
    return NextResponse.json({ message: `Synced ${count} models from PHP references.` });
  } catch (err) {
    console.error('[POST /api/models/sync]', err);
    return NextResponse.json({ error: 'Sync failed' }, { status: 500 });
  }
}
