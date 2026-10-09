import { NextResponse } from 'next/server';
import { saveSeeder, getRecentSeeders } from '@/lib/db';

/** GET /api/seeders — returns the 50 most-recent saved seeder entries. */
export async function GET() {
  try {
    const rows = await getRecentSeeders(50);
    return NextResponse.json(rows);
  } catch (err) {
    console.error('[GET /api/seeders]', err);
    return NextResponse.json({ error: 'Failed to fetch seeders' }, { status: 500 });
  }
}

/**
 * POST /api/seeders
 * Body: { seederType, company, output, questions?: [{key, value}] }
 */
export async function POST(req: Request) {
  try {
    const body = await req.json() as {
      seederType: string;
      company: string;
      output: string;
      questions?: { key: string; value: string }[];
    };

    if (!body.seederType || !body.company || !body.output) {
      return NextResponse.json({ error: 'seederType, company and output are required' }, { status: 400 });
    }

    const id = await saveSeeder(body);
    return NextResponse.json({ id }, { status: 201 });
  } catch (err) {
    console.error('[POST /api/seeders]', err);
    return NextResponse.json({ error: 'Failed to save seeder' }, { status: 500 });
  }
}
