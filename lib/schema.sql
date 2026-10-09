-- ─────────────────────────────────────────────────────────────────────────────
-- Seeder Tool — Neon (Postgres) schema
-- Run via: neon deploy   OR   psql $DATABASE_URL -f lib/schema.sql
-- ─────────────────────────────────────────────────────────────────────────────

-- Models ──────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS models (
  id         SERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL UNIQUE,
  created_at TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
  updated_at TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- Model Columns ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS model_columns (
  id           SERIAL PRIMARY KEY,
  model_id     INTEGER      NOT NULL REFERENCES models(id) ON DELETE CASCADE,
  column_name  VARCHAR(255) NOT NULL,
  sort_order   INTEGER      NOT NULL DEFAULT 0,
  UNIQUE (model_id, column_name)
);

CREATE INDEX IF NOT EXISTS idx_model_columns_model_id ON model_columns(model_id);

-- Seeders ─────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS seeders (
  id           SERIAL PRIMARY KEY,
  seeder_type  VARCHAR(100) NOT NULL,
  company      VARCHAR(100) NOT NULL,
  output       TEXT         NOT NULL,
  created_at   TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

-- Seeder Questions ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS seeder_questions (
  id              SERIAL PRIMARY KEY,
  seeder_id       INTEGER      NOT NULL REFERENCES seeders(id) ON DELETE CASCADE,
  question_key    VARCHAR(255) NOT NULL,
  question_value  TEXT
);

CREATE INDEX IF NOT EXISTS idx_seeder_questions_seeder_id ON seeder_questions(seeder_id);
