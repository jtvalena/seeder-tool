import { neon } from '@neondatabase/serverless';

/** Lazily-created Neon SQL client — only instantiated on first call, not at module load. */
function getDb() {
  if (!process.env.DATABASE_URL) {
    throw new Error('DATABASE_URL environment variable is not set.');
  }
  return neon(process.env.DATABASE_URL);
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

/** Returns every model with its columns as an array (same shape as models-generated.json). */
export async function getAllModels(): Promise<Record<string, string[]>> {
  const sql = getDb();
  const rows = await sql`
    SELECT m.name, mc.column_name, mc.sort_order
    FROM   models m
    JOIN   model_columns mc ON mc.model_id = m.id
    ORDER  BY m.name, mc.sort_order, mc.column_name
  `;

  const dict: Record<string, string[]> = {};
  for (const row of rows) {
    if (!dict[row.name]) dict[row.name] = [];
    dict[row.name].push(row.column_name);
  }
  return dict;
}

/** Replaces ALL models & columns with the supplied dictionary (full sync). */
export async function syncModels(models: Record<string, string[]>): Promise<void> {
  const sql = getDb();
  // Full replace — delete all then re-insert (model_columns cascade on delete).
  await sql`DELETE FROM models`;

  for (const [name, columns] of Object.entries(models)) {
    const [{ id }] = await sql`
      INSERT INTO models (name) VALUES (${name})
      ON CONFLICT (name) DO UPDATE SET updated_at = NOW()
      RETURNING id
    `;
    for (let i = 0; i < columns.length; i++) {
      await sql`
        INSERT INTO model_columns (model_id, column_name, sort_order)
        VALUES (${id}, ${columns[i]}, ${i})
        ON CONFLICT (model_id, column_name) DO UPDATE SET sort_order = ${i}
      `;
    }
  }
}

/** Saves a generated seeder entry and its question/value pairs. */
export async function saveSeeder(params: {
  seederType: string;
  company: string;
  output: string;
  questions?: { key: string; value: string }[];
}): Promise<number> {
  const sql = getDb();
  const [{ id }] = await sql`
    INSERT INTO seeders (seeder_type, company, output)
    VALUES (${params.seederType}, ${params.company}, ${params.output})
    RETURNING id
  `;

  for (const q of params.questions ?? []) {
    await sql`
      INSERT INTO seeder_questions (seeder_id, question_key, question_value)
      VALUES (${id}, ${q.key}, ${q.value})
    `;
  }

  return id;
}

/** Fetches the N most-recent seeder entries with their questions. */
export async function getRecentSeeders(limit = 50) {
  const sql = getDb();
  const seeders = await sql`
    SELECT id, seeder_type, company, output, created_at
    FROM   seeders
    ORDER  BY created_at DESC
    LIMIT  ${limit}
  `;

  const questions = seeders.length
    ? await sql`
        SELECT seeder_id, question_key, question_value
        FROM   seeder_questions
        WHERE  seeder_id = ANY(${seeders.map((s: Record<string, unknown>) => s.id)})
      `
    : [];

  return seeders.map((s: Record<string, unknown>) => ({
    ...s,
    questions: (questions as Record<string, unknown>[])
      .filter((q) => q.seeder_id === s.id)
      .map((q) => ({
        key: q.question_key as string,
        value: q.question_value as string,
      })),
  }));
}
