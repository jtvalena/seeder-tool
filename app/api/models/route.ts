import { NextResponse } from 'next/server';
import { getAllModels, syncModels } from '@/lib/db';

/** GET /api/models — returns all models with columns from the DB. */
export async function GET() {
  try {
    const models = await getAllModels();
    return NextResponse.json(models);
  } catch (err) {
    console.error('[GET /api/models]', err);
    return NextResponse.json({ error: 'Failed to fetch models' }, { status: 500 });
  }
}

/** PUT /api/models — full sync of the models dictionary. */
export async function PUT(req: Request) {
  try {
    const body = await req.json() as Record<string, string[]>;
    if (typeof body !== 'object' || Array.isArray(body)) {
      return NextResponse.json({ error: 'Body must be a JSON object' }, { status: 400 });
    }
    await syncModels(body);
    const count = Object.keys(body).length;
    return NextResponse.json({ synced: count });
  } catch (err) {
    console.error('[PUT /api/models]', err);
    return NextResponse.json({ error: 'Failed to sync models' }, { status: 500 });
  }
}
